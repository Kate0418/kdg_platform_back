<?php

namespace App\Http\Controllers;

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
            ->where("type", 3)
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
                        "courseName" => $query->student->course
                            ? $query->student->course->name
                            : null,
                        "gradeName" => $query->student->grade->name,
                        "yearName" => $query->student->year->name,
                    ];
                }),
                "total" => $total,
            ],
            201
        );
    }

    // students: [{name: "yuto", email: "yuto@gmail.com", courseId: null, gradeId: 7, yearId: 3}]
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
                    $user_id = User::create([
                        "name" => $student["name"],
                        "password" => bcrypt($first_password),
                        "type" => 3,
                        "company_id" => $company_id,
                        "email" => $student["email"],
                        "first_password" => $first_password,
                    ])->id;

                    Student::create([
                        "user_id" => $user_id,
                        "course_id" => $student["courseId"],
                        "grade_id" => $student["gradeId"],
                        "year_id" => $student["yearId"],
                    ]);
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
}
