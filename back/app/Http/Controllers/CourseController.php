<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Course;

class CourseController extends Controller
{
    public function select() {
        $user = Auth::user();
        $courses = Course::where('company_id', $user->company_id)
            ->select('id', 'name')
            ->get()
            ->map(function ($course) {
                return ['id' => $course->id, 'name' => $course->name];
            });
        return response()->json(['courses' => $courses]);
    }
}