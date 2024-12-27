<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Support\Facades\Log;

class TeacherController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        $teachers = User::where("company_id", $user->company_id)
            ->where("type", 2)
            ->with("subject");

        $key_word = $request->keyWord;
        if ($key_word) {
            $teachers->where(function ($query) use ($key_word) {
                $query
                    ->where("name", "like", "%{$key_word}%")
                    ->orWhere("email", "like", "%{$key_word}%")
                    ->orWhereHas("subject", function ($subQuery) use (
                        $key_word
                    ) {
                        $subQuery->where("name", "like", "%{$key_word}%");
                    });
            });
        }

        $page_count = $request->pageCount;
        $teacher_ids = $teachers->pluck("id")->toArray();
        $teachers = $teachers->paginate(14, ["*"], "page", $page_count);
        $total = $teachers->lastPage();

        return response()->json(
            [
                "success" => true,
                "teachers" => $teachers->map(function ($query) {
                    return [
                        "id" => $query->id,
                        "name" => $query->name,
                        "email" => $query->email,
                        "subjects" => $query->subject
                            ? $query->subject
                                ->map(function ($subQuery) {
                                    return [
                                        "id" => $subQuery->id,
                                        "name" => $subQuery->name,
                                    ];
                                })
                                ->toArray()
                            : [],
                    ];
                }),
                "teacherIds" => $teacher_ids,
                "total" => $total,
            ],
            201
        );
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $request->validate([
            "teachers.*.name" => "required|string|max:255",
            "teachers.*.email" => "required|email|unique:users,email",
            "teachers.*.subjectIds" => "array",
        ]);

        $teachers = $request->teachers;
        $company_id = $user->company_id;
        $emails = collect($teachers)->pluck("email");

        $unique_emails = $emails->unique();
        if ($emails->count() !== $unique_emails->count()) {
            return response()->json(
                [
                    "success" => false,
                    "message" => "登録するメールアドレスに重複があります。",
                ],
                422
            );
        }

        try {
            DB::transaction(function () use ($teachers, $company_id) {
                foreach ($teachers as $teachers) {
                    $first_password = Str::random(12);
                    $user = User::create([
                        "name" => $teachers["name"],
                        "password" => bcrypt($first_password),
                        "type" => 2,
                        "company_id" => $company_id,
                        "email" => $teachers["email"],
                        "first_password" => $first_password,
                    ]);

                    $user_id = $user->id;
                    $subject_ids = collect($teachers["subjectIds"])
                        ->map(function ($subject_id) {
                            return $subject_id;
                        })
                        ->toArray();

                    $subjects = Subject::whereIn("id", $subject_ids)->get();

                    foreach ($subjects as $subject) {
                        $subject->user_id = $user_id;
                        $subject->save();
                    }
                }
            });
        } catch (Exception $e) {
            Log::error($e);
            return response()->json(
                [
                    "success" => false,
                    "message" => "講師の登録に失敗しました。",
                ],
                500
            );
        }

        return response()->json(
            [
                "success" => true,
                "message" => "講師を登録しました。",
            ],
            201
        );
    }

    public function update(Request $request)
    {
        $request->validate([
            "teachers.*.id" => "required|integer",
            "teachers.*.name" => "required|string|max:255",
            "teachers.*.email" => "required|email",
            "teachers.*.subjectIds" => "array",
        ]);

        $teachers = [];
        $subjects = [];
        foreach ($request->teachers as $teacher) {
            $teachers[] = [
                "id" => $teacher["id"],
                "name" => $teacher["name"],
                "email" => $teacher["email"],
            ];
            foreach ($teacher["subjectIds"] as $subject_id) {
                $subjects[] = [
                    "id" => $subject_id,
                    "user_id" => $teacher["id"],
                ];
            }
        }

        try {
            DB::transaction(function () use ($teachers, $subjects) {
                User::bulkUpdate($teachers, ["name", "email"]);
                Subject::whereIn('user_id', collect($teachers)->pluck('id'))
                ->update(['user_id' => null]);
                Subject::bulkUpdate($subjects, ['user_id']);
            });
        } catch(Exception $e) {
            Log::warning($e);
            return response()->json(
                [
                    "success" => false,
                    "message" => "講師の更新に失敗しました。",
                ],
                500
            );
        }

        return response()->json(
            [
                "success" => true,
                "message" => "講師を更新しました。",
            ],
            201
        );
    }

    public function destroy(Request $request)
    {
        $request->validate([
            "teacherIds" => "required|array",
            "teacherIds.*" => "required|integer",
        ]);

        $teacher_ids = $request->teacherIds;

        try {
            DB::transaction(function () use ($teacher_ids) {
                User::whereIn("id", $teacher_ids)->delete();
                Subject::whereIn("user_id", $teacher_ids)->update(['user_id' => null]);
            });
        } catch(Exception $e) {
            Log::warning($e);
            return response()->json(
                [
                    "success" => false,
                    "message" => "講師の削除に失敗しました。",
                ],
                500
            );
        }

        return response()->json(
            [
                "success" => true,
                "message" => "講師を削除しました。",
            ],
            201
        );
    }

    public function select()
    {
        $user = Auth::user();

        $teachers = User::where("company_id", $user->company_id)
            ->where("type", 2)
            ->get()
            ->map(function ($query) {
                return [
                    "value" => $query->id,
                    "label" => $query->name,
                ];
            });

        return response()->json([
            "success" => true,
            "teachers" => $teachers,
        ]);
    }
}
