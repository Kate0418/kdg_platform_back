<?php

use App\Http\Controllers\LoginController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\TeacherController;
use App\Models\Grade;
use App\Models\Year;
use Illuminate\Support\Facades\Route;

Route::middleware("auth:sanctum")->group(function () {
    Route::get("/token", function () {
        return response()->json(["success" => true]);
    });

    Route::prefix("student")->group(function () {
        Route::apiResource("/", StudentController::class);
        Route::post("/add", [StudentController::class, "add"]);
    });

    Route::prefix("subject")->group(function () {
        Route::apiResource("/", SubjectController::class);
        Route::get("/select", [SubjectController::class, "select"]);
    });

    Route::prefix("course")->group(function () {
        Route::apiResource("/", CourseController::class);
        Route::get("/select", [CourseController::class, "select"]);
    });

    Route::prefix("teacher")->group(function () {
        Route::apiResource("/", TeacherController::class);
        Route::get("/select", [TeacherController::class, "select"]);
    });

    Route::get("/grade/select", function () {
        $grades = Grade::get()->map(function ($query) {
            return [
                "value" => $query->id,
                "label" => $query->name,
            ];
        });
        return response()->json([
            "success" => true,
            "grades" => $grades,
        ]);
    });

    Route::get("/year/select", function () {
        $years = Year::get()->map(function ($query) {
            return [
                "value" => $query->id,
                "label" => $query->name,
            ];
        });
        return response()->json([
            "success" => true,
            "years" => $years,
        ]);
    });
});

Route::post("/login", [LoginController::class, "index"]);
