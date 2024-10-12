<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Support\Facades\Log;

class StudentController extends Controller
{
    public function get_users (Request $request) {
        $user = Auth::user();

        $request->validate([
            'key_word' => 'nullable|string',
            'course_id' => 'nullable|integer',
        ]);

        $users = User::where('company_id', $user->company_id)
            ->where('type', 3)
            ->with('course');
        if ($request->key_word) {
            $key_word = $request->key_word;
            $users->where('name', 'like', '%'.$key_word.'%')
                ->orWhere('email', 'like', '%'.$key_word.'%');
        }
        if ($request->course_id) {
            $course_id = $request->course_id;
            $users->where('course_id', $course_id);
        }

        $users = $users->select([
            'id',
            'name',
            'email',
            'course_id'
        ])->get();

        return response()->json($users);
    }

    public function add (Request $request) {
        $user = Auth::user();

        $request->validate([
            'students.*.name' => 'required|string|max:255',
            'students.*.email' => 'required|email|unique:users,email',
            'students.*.courseId' => 'nullable|integer',
        ]);

        $students = $request->students;
        $company_id = $user->company_id;
        $emails = collect($students)->pluck('email');

        $unique_emails = $emails->unique();
        if ($emails->count() !== $unique_emails->count()) {
            return response()->json([
                'success' => false,
                "message" => "登録するメールアドレスに重複があります。"
            ], 422);
        }

        $user_emails = User::pluck('email');
        if ($emails->intersect($user_emails)->isNotEmpty()) {
            return response()->json([
                'success' => false,
                "message" => "既に利用されているメールアドレスが含まれています。"
            ], 422);
        }

        try {
            DB::transaction(function () use ($students, $company_id) {
                foreach ($students as $student) {
                    $first_password = Str::random(12);
                    User::create([
                        'name' => $student["name"],
                        'password' => bcrypt($first_password),
                        'type' => 3,
                        'course_id' => $student["courseId"],
                        'company_id' => $company_id,
                        'email' => $student["email"],
                        'first_password' => $first_password
                    ]);
                }
            });
        } catch (Exception $e) {
            Log::error($e);
            return response()->json([
                'success' => false,
                "message" => "生徒の登録に失敗しました。"
            ], 500);
        }

        return response()->json([
            'success' => true,
            "message" => "生徒を追加しました。"
        ], 201);
    }
}