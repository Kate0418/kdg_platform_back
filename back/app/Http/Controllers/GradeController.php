<?php

namespace App\Http\Controllers;

use App\Models\Grade;

class GradeController extends Controller
{
    public function select()
    {
        $grades = Grade::get()->map(function ($grade) {
            return [
                "value" => $grade->id,
                "label" => $grade->name,
            ];
        });
        return response()->json([
            "success" => true,
            "grades" => $grades,
        ]);
    }
}
