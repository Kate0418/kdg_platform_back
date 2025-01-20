<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Course;
use App\Models\Period;
use Illuminate\Support\Facades\Log;
use Exception;
use Carbon\Carbon;

class CourseController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        $courses = Course::where("company_id", $user->company_id)->with(
            "masterGrade"
        );

        $key_word = $request->keyWord;
        if ($key_word) {
            $courses->where(function ($query) use ($key_word) {
                $query
                    ->where("name", "like", "%{$key_word}%")
                    ->orWhereHas("masterGrade", function ($subQuery) use ($key_word) {
                        $subQuery->where("name", "like", "%{$key_word}%");
                    });
            });
        }

        $page_count = $request->pageCount;
        $course_ids = $courses->pluck("id")->toArray();
        $courses = $courses->paginate(14, ["*"], "page", $page_count);
        $total = $courses->lastPage();

        return response()->json(
            [
                "success" => true,
                "courses" => $courses->map(function ($query) {
                    return [
                        "id" => $query->id,
                        "name" => $query->name,
                        "gradeName" => $query->masterGrade->name,
                    ];
                }),
                "courseIds" => $course_ids,
                "total" => $total,
            ],
            201
        );
    }

    public function destroy(Request $request)
    {
        $request->validate([
            "courseIds" => "required|array",
            "courseIds.*" => "required|integer",
        ]);

        $courseIds = $request->courseIds;

        try {
            DB::transaction(function () use ($courseIds) {
                Course::whereIn("id", $courseIds)->delete();
            });
        } catch (Exception $e) {
            Log::error($e);
            return response()->json(
                [
                    "success" => false,
                    "message" => "コースの削除に失敗しました。",
                ],
                500
            );
        }

        return response()->json(
            [
                "success" => true,
                "message" => "コースを削除しました。",
            ],
            200
        );
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $request->validate([
            'course' => 'required|array',
            'course.name' => 'required|string',
            'course.gradeId' => 'nullable|integer',
            'course.periods' => 'required|array',
            'course.periods.*.sequence' => 'required|integer|min:1',
            'course.periods.*.startTime' => 'nullable|date',
            'course.periods.*.endTime' => 'nullable|date|after:course.periods.*.startTime',
            'course.periods.*.lessons' => 'required|array',
            'course.periods.*.lessons.*.subjectId' => 'nullable|integer',
            'course.periods.*.lessons.*.dayOfWeek' => 'required|in:Mon,Tue,Wed,Thu,Fri,Sat,Sun',
        ]);

        $company_id = $user->company_id;
        $course = $request->course;

        try {
            DB::transaction(function () use ($course, $company_id) {
                $created_course = Course::create([
                    "name" => $course["name"],
                    "master_grade_id" => $course["gradeId"],
                    "company_id" => $company_id,
                ]);

                $periods = collect($course["periods"])
                    ->map((function ($period) {
                        return [
                            "sequence" => $period["sequence"],
                            "start_time" => $period["startTime"],
                            "end_time" => $period["endTime"],
                            "lessons" => $period["lessons"],
                        ];
                    }))
                    ->toArray();

                foreach ($periods as $period) {
                    $created_period = Period::create([
                        "course_id" => $created_course->id,
                        "sequence" => $period["sequence"],
                        "start_time" => Carbon::parse($period["start_time"])
                            ->timezone('Asia/Tokyo')
                            ->format("H:i:s"),
                        "end_time" => Carbon::parse($period["end_time"])
                            ->timezone('Asia/Tokyo')
                            ->format("H:i:s"),
                    ]);

                    $created_period->lessons()->createMany(
                        collect($period["lessons"])->map(function ($lesson) use ($company_id) {
                            return [
                                "company_id" => $company_id,
                                "subject_id" => $lesson["subjectId"],
                                "day_of_week" => $lesson["dayOfWeek"],
                            ];
                        })->toArray()
                    );
                }
            });
        } catch (Exception $e) {
            Log::error($e);
            return response()->json(
                [
                    "success" => false,
                    "message" => "コースの登録に失敗しました。",
                ],
                500
            );
        }

        return response()->json(
            [
                "success" => true,
                "message" => "コースを追加しました。",
            ],
            201
        );
    }

    public function select()
    {
        $user = Auth::user();
        $courses = Course::where("company_id", $user->company_id)
            ->get()
            ->map(function ($course) {
                return [
                    "value" => $course->id,
                    "label" => $course->name,
                ];
            });
        return response()->json([
            "success" => true,
            "courses" => $courses,
        ]);
    }
}
