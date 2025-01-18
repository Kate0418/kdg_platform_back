<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class TopController extends Controller
{
    public function attend() {
        $user = Auth::user();
        $now = Carbon::now();

        if ($user->type === "student") {
            $course = $user->student
            ->course()
            ->whereHas('time', function ($query) use ($now) {
                $query->where("start_time", "<=", $now->addMinutes(2)->format('H:i:s'))
                    ->where("end_time", ">=", $now->subMinutes(1)->format('H:i:s'));
            })
            ->with([
                    'time',
                    'lessons' => function ($query) use ($now) {
                    $query->where("day_of_week", $now->format("D"))
                        ->with('subject');
            }])
            ->first();

            $time = $course->time;
            $lessons = $course?->lessons
                ->map(function($lesson) use($time) {
                    return [
                        "id" => $lesson["id"],
                        "subject" => $lesson["subject"]["name"],
                        "start_time" => $time->firstWhere("period", $lesson["period"])->start_time,
                        "end_time" => $time->firstWhere("period", $lesson["period"])->end_time,
                    ];
                }) ?? [];

            return response()->json([
                "success" => true,
                "lessons" => $lessons,
            ]);
        }

        return response()->json([
            "success" => false,
        ]);
    }
}
