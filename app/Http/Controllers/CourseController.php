<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Course;
use App\Models\Lesson;
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
            "grade"
        );

        $key_word = $request->keyWord;
        if ($key_word) {
            $courses->where(function ($query) use ($key_word) {
                $query
                    ->where("name", "like", "%{$key_word}%")
                    ->orWhereHas("grade", function ($subQuery) use ($key_word) {
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
                        "gradeName" => $query->grade->name,
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
            "course.name" => "required|string|max:255",
            "course.gradeId" => "required|integer",

            "course.times.*.period" => "required|integer",
            "course.times.*.startTime" => "required|string|max:255",
            "course.times.*.endTime" => "required|string|max:255",

            "course.lessons.*.dayOfWeek" => "required|in:Mon,Tue,Wed,Thu,Fri,Sat,Sun",
            "course.lessons.*.period" => "required|integer",
            "course.lessons.*.subjectId" => "required|integer",
        ]);

        $company_id = $user->company_id;
        $course = $request->course;

        try {
            DB::transaction(function () use ($course, $company_id) {
                $course_id = Course::create([
                    "name" => $course["name"],
                    "grade_id" => $course["gradeId"],
                    "company_id" => $company_id,
                ])->id;

                foreach ($course["lessons"] as $lesson) {
                    Lesson::create([
                        "course_id" => $course_id,
                        "subject_id" => $lesson["subjectId"],
                        "day_of_week" => $lesson["dayOfWeek"],
                        "period" => $lesson["period"],
                        "company_id" => $company_id,
                    ]);
                }

                foreach ($course["times"] as $time) {
                    Period::create([
                        "course_id" => $course_id,
                        "period" => $time["period"],
                        "start_time" => Carbon::parse(
                            $time["startTime"]
                        )->format("H:i:s"),
                        "end_time" => Carbon::parse($time["endTime"])->format(
                            "H:i:s"
                        ),
                    ]);
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
