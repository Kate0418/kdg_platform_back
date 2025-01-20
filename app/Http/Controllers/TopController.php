<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class TopController extends Controller
{
    public function attendStudent() {
        $user = Auth::user();
        $now = Carbon::now();

        $course = $user->student
        ->course()
        ->whereHas('periods', function ($query) use ($now) {
            $query->where("start_time", "<=", $now->addMinutes(2)->format('H:i:s'))
                ->where("end_time", ">=", $now->subMinutes(1)->format('H:i:s'));
        })
        ->with(["periods.lessons" => function($query) use ($now) {
            $query->where("day_of_week", $now->format("D"))
                ->with("subject");
        }])
        ->first();

        $lessons = [];
        if ($course) {
            foreach ($course->periods as $period) {
                foreach ($period->lessons as $lesson) {
                    $start_time = Carbon::parse($period->start_time);
                    $end_time = Carbon::parse($period->end_time);

                    if ($lesson->whereHas("attends", function ($query) use ($user, $lesson, $now) {
                        $query->where("student_id", $user->student->id)
                            ->where("lesson_id", $lesson->id)
                            ->where("date", $now->format('Y-m-d'));
                    })->exists()) {
                        $starts = "already";
                    } else if ($now->between($start_time->subMinutes(2), $start_time->addMinutes(2))) {
                        $starts = "present";
                    } else {
                        $starts = "late";
                    }

                    $lessons[] = [
                        "id" => $lesson->id,
                        "subjectName" => $lesson->subject->name,
                        "startTime" => $start_time->format("H:i"),
                        "endTime" => $end_time->format("H:i"),
                        "states" => $starts,
                    ];
                }
            }
        }

        return response()->json([
            "success" => true,
            "lessons" => $lessons,
        ]);
    }
}
