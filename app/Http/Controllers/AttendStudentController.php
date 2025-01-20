<?php

namespace App\Http\Controllers;

use App\Models\Attend;
use App\Models\Lesson;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AttendStudentController extends Controller
{
    public function store(Request $request)
    {
        $user = Auth::user();
        $now = Carbon::now();
        $lesson_id = $request->lessonId;
        $lesson = Lesson::find($lesson_id);

        if ($lesson->whereHas("attends", function ($query) use ($user, $lesson, $now) {
            $query->where("student_id", $user->student->id)
                ->where("lesson_id", $lesson->id)
                ->where("date", $now->format('Y-m-d'));
        })->exists()) {
            return response()->json(
                [
                    "success" => true,
                    "message" => "既に出席登録しています。",
                ],
                201
            );
        }
        
        $period = $lesson->period;
        $start_time = Carbon::parse($period->start_time);
        $end_time = Carbon::parse($period->end_time);

        if ($now->between($start_time->subMinutes(2), $start_time->addMinutes(2))) {
            $status = "present";
        } else if ($now->lt($end_time->addMinutes(1))) {
            $status = "late";
        } else {
            return response()->json(
                [
                    "success" => false,
                    "message" => "出席登録に失敗しました。",
                ],
                500
            );
        }

        try {
            DB::transaction(function () use ($user, $lesson_id, $now, $status) {
                Attend::create([
                    "company_id" => $user->company_id,  
                    "student_id" => $user->student->id,
                    "lesson_id" => $lesson_id,
                    "date" => $now->format('Y-m-d'),
                    "states" => $status,
                ]);
            });
        } catch (Exception $e) {
            Log::warning($e);
            return response()->json(
                [
                    "success" => false,
                    "message" => "出席登録に失敗しました。",
                ],
                500
            );
        }

        return response()->json(
            [
                "success" => true,
                "message" => "出席登録しました。",
            ],
            201
        );
    }
}
