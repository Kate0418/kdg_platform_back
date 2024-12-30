<?php

namespace App\Http\Controllers;

use App\Jobs\SendMailJob;
use App\Mail\LoginMail;
use App\Models\User;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Support\Facades\Log;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            "keyWord" => "nullable|string",
            "pageCount" => "integer",
        ]);

        $users = User::where("company_id", $user->company_id)
            ->where("type", "student")
            ->with([
                "student",
                "student.course",
                "student.grade",
                "student.year",
            ]);

        $key_word = $request->keyWord;
        if ($key_word) {
            $users->where(function ($query) use ($key_word) {
                $query
                    ->where("name", "like", "%" . $key_word . "%")
                    ->orWhere("email", "like", "%" . $key_word . "%")
                    ->orWhereHas("student.course", function ($subQuery) use (
                        $key_word
                    ) {
                        $subQuery->where("name", "like", "%{$key_word}%");
                    })
                    ->orWhereHas("student.grade", function ($subQuery) use (
                        $key_word
                    ) {
                        $subQuery->where("name", "like", "%{$key_word}%");
                    })
                    ->orWhereHas("student.year", function ($subQuery) use (
                        $key_word
                    ) {
                        $subQuery->where("name", "like", "%{$key_word}%");
                    });
            });
        }

        $page_count = $request->pageCount;
        $student_ids = $users->pluck("id")->toArray();
        $users = $users->paginate(14, ["*"], "page", $page_count);
        $total = $users->lastPage();

        return response()->json(
            [
                "success" => true,
                "students" => $users->map(function ($query) {
                    return [
                        "id" => $query->id,
                        "name" => $query->name,
                        "email" => $query->email,
                        "course" => $query->student->course
                            ? [
                                "id" => $query->student->course->id,
                                "name" => $query->student->course->name,
                            ]
                            : null,
                        "grade" => [
                            "id" => $query->student->grade->id,
                            "name" => $query->student->grade->name,
                        ],
                        "year" => [
                            "id" => $query->student->year->id,
                            "name" => $query->student->year->name,
                        ],
                    ];
                }),
                "studentIds" => $student_ids,
                "total" => $total,
            ],
            201
        );
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            "students.*.name" => "required|string|max:255",
            "students.*.email" => "required|email|unique:users,email",
            "students.*.courseId" => "nullable|integer",
            "students.*.gradeId" => "required|integer",
            "students.*.yearId" => "required|integer",
        ]);

        $students = $request->students;
        $company_id = $user->company_id;
        $emails = collect($students)->pluck("email");

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

        $user_emails = User::pluck("email");
        if ($emails->intersect($user_emails)->isNotEmpty()) {
            return response()->json(
                [
                    "success" => false,
                    "message" =>
                        "既に利用されているメールアドレスが含まれています。",
                ],
                422
            );
        }

        try {
            DB::transaction(function () use ($students, $company_id) {
                foreach ($students as $student) {
                    $first_password = Str::random(12);
                    $new_user = User::create([
                        "name" => $student["name"],
                        "password" => bcrypt($first_password),
                        "type" => "student",
                        "company_id" => $company_id,
                        "email" => $student["email"],
                        "first_password" => $first_password,
                    ]);

                    Student::create([
                        "user_id" => $new_user->id,
                        "course_id" => $student["courseId"],
                        "grade_id" => $student["gradeId"],
                        "year_id" => $student["yearId"],
                    ]);

                    SendMailJob::dispatch($new_user, LoginMail::class);
                }
            });
        } catch (Exception $e) {
            Log::error($e);
            return response()->json(
                [
                    "success" => false,
                    "message" => "生徒の登録に失敗しました。",
                ],
                500
            );
        }

        return response()->json(
            [
                "success" => true,
                "message" => "生徒を追加しました。",
            ],
            201
        );
    }

    public function update(Request $request)
    {
        $request->validate([
            "students.*.name" => "required|string|max:255",
            "students.*.email" => "required|email",
            "students.*.courseId" => "nullable|integer",
            "students.*.gradeId" => "required|integer",
            "students.*.yearId" => "required|integer",
        ]);

        $users = [];
        $students = [];
        foreach ($request->students as $student) {
            $users[] = [
                "id" => $student["id"],
                "name" => $student["name"],
                "email" => $student["email"],
            ];

            $students[] = [
                "user_id" => $student["id"],
                "course_id" => $student["courseId"],
                "grade_id" => $student["gradeId"],
                "year_id" => $student["yearId"],
            ];
        }

        try {
            DB::transaction(function () use ($users, $students) {
                User::bulkUpdate($users, ["name", "email"]);
                Student::bulkUpdate($students, ["course_id", "grade_id", "year_id"]);
            });
        } catch(Exception $e) {
            Log::warning($e);
            return response()->json(
                [
                    "success" => false,
                    "message" => "生徒の更新に失敗しました。",
                ],
                500
            );
        }

        return response()->json(
            [
                "success" => true,
                "message" => "生徒を更新しました。",
            ],
            201
        );
    }

    public function destroy(Request $request)
    {
        $request->validate([
            "studentIds" => "required|array",
            "studentIds.*" => "required|integer",
        ]);

        $student_ids = $request->studentIds;

        try {
            DB::transaction(function () use ($student_ids) {
                User::whereIn("id", $student_ids)->delete();
            });
        } catch (Exception $e) {
            Log::warning($e);
            return response()->json(
                [
                    "success" => false,
                    "message" => "生徒の削除に失敗しました。",
                ],
                500
            );
        }

        return response()->json(
            [
                "success" => true,
                "message" => "生徒を削除しました。",
            ],
            201
        );
    }
}
