<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Course;

class CourseController extends Controller
{
    public function select() {
        $user = Auth::user();
        $courses = Course::where('company_id', $user->company_id)
            ->get()
            ->map(function ($course) {
                return [
                    'value' => $course->id,
                    'label' => $course->name
                ];
            });
        return response()->json([
            'success' => true,
            'courses' => $courses
        ]);
    }
}