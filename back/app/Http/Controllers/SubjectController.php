<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Support\Facades\Log;

class SubjectController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        $subjects = Subject::where("company_id", $user->company_id)->with(
            "user"
        );

        $key_word = $request->keyWord;
        if ($key_word) {
            $subjects->where(function ($query) use ($key_word) {
                $query
                    ->where("name", "like", "%{$key_word}%")
                    ->orWhereHas("user", function ($subQuery) use ($key_word) {
                        $subQuery->where("name", "like", "%{$key_word}%");
                    });
            });
        }

        $page_count = $request->pageCount;
        $student_ids = $subjects->pluck("id")->toArray();
        $subjects = $subjects->paginate(14, ["*"], "page", $page_count);
        $total = $subjects->lastPage();

        return response()->json(
            [
                "success" => true,
                "subjects" => $subjects->map(function ($query) {
                    return [
                        "id" => $query->id,
                        "name" => $query->name,
                        "teacher" => $query->user
                            ? [
                                "id" => $query->user->id,
                                "name" => $query->user->name,
                            ]
                            : null,
                    ];
                }),
                "subjectIds" => $student_ids,
                "total" => $total,
            ],
            201
        );
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            "subjects.*.name" => "required|string|max:255",
            "subjects.*.teacherId" => "integer|nullable",
        ]);

        $subjects = $request->subjects;
        $company_id = $user->company_id;

        try {
            DB::transaction(function () use ($subjects, $company_id) {
                foreach ($subjects as $subject) {
                    Subject::create([
                        "name" => $subject["name"],
                        "user_id" =>
                            $subject["teacherId"] !== null
                                ? $subject["teacherId"]
                                : null,
                        "company_id" => $company_id,
                    ]);
                }
            });
        } catch (Exception $e) {
            Log::error($e);
            return response()->json(
                [
                    "success" => false,
                    "message" => "科目の登録に失敗しました。",
                ],
                500
            );
        }

        return response()->json(
            [
                "success" => true,
                "message" => "科目を登録しました。",
            ],
            201
        );
    }

    public function update(Request $request)
    {
        $request->validate([
            "subjects.*.id" => "required|integer",
            "subjects.*.name" => "required|string|max:255",
            "subjects.*.teacherId" => "integer|nullable",
        ]);

        $subjects = array_map(function ($subject) {
            return [
                "id" => $subject["id"],
                "name" => $subject["name"],
                "user_id" => $subject["teacherId"],
            ];
        }, $request->subjects);

        try {
            DB::transaction(function () use ($subjects) {
                Subject::bulkUpdate($subjects, ["name", "user_id"]);
            });
        } catch (Exception $e) {
            Log::error($e);
            return response()->json(
                [
                    "success" => false,
                    "message" => "保存に失敗しました。",
                ],
                500
            );
        }

        return response()->json(
            [
                "success" => true,
                "message" => "変更を保存しました。",
            ],
            200
        );
    }

    public function destroy(Request $request)
    {
        $request->validate([
            "subjectIds" => "required|array",
            "subjectIds.*" => "required|integer",
        ]);

        $subjectIds = $request->subjectIds;

        try {
            DB::transaction(function () use ($subjectIds) {
                Subject::whereIn("id", $subjectIds)->delete();
            });
        } catch (Exception $e) {
            Log::error($e);
            return response()->json(
                [
                    "success" => false,
                    "message" => "科目の削除に失敗しました。",
                ],
                500
            );
        }

        return response()->json(
            [
                "success" => true,
                "message" => "科目を削除しました。",
            ],
            200
        );
    }

    public function select()
    {
        $user = Auth::user();

        $subjects = Subject::where("company_id", $user->company_id)
            ->with("user")
            ->get()
            ->map(function ($query) {
                return [
                    "value" => $query->id,
                    "label" =>
                        $query->name .
                        ": " .
                        ($query->user ? $query->user->name : "講師なし"),
                ];
            });

        return response()->json(
            [
                "success" => true,
                "subjects" => $subjects,
            ],
            201
        );
    }
}
