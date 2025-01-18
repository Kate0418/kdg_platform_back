<?php

use App\Http\Controllers\LoginController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\TopController;
use App\Models\MasterGrade;
use App\Models\MasterYear;
use Illuminate\Support\Facades\Route;

Route::middleware("auth:sanctum")->group(function () {
    Route::get("/token", function () {
        return response()->json(["success" => true]);
    });

    Route::prefix("student")->group(function () {
        Route::get("/", [StudentController::class, "index"]);
        Route::post("/", [StudentController::class, "store"]);
        Route::put("/", [StudentController::class, "update"]);
        Route::delete("/", [StudentController::class, "destroy"]);
    });

    Route::prefix("subject")->group(function () {
        Route::get("/", [SubjectController::class, "index"]);
        Route::post("/", [SubjectController::class, "store"]);
        Route::put("/", [SubjectController::class, "update"]);
        Route::delete("/", [SubjectController::class, "destroy"]);

        Route::get("/select", [SubjectController::class, "select"]);
    });

    Route::prefix("course")->group(function () {
        Route::get("/", [CourseController::class, "index"]);
        Route::post("/", [CourseController::class, "store"]);
        // Route::put("/", [CourseController::class, "update"]);
        Route::delete("/", [CourseController::class, "destroy"]);

        Route::get("/select", [CourseController::class, "select"]);
    });

    Route::prefix("teacher")->group(function () {
        Route::get("/", [TeacherController::class, "index"]);
        Route::post("/", [TeacherController::class, "store"]);
        Route::put("/", [TeacherController::class, "update"]);
        Route::delete("/", [TeacherController::class, "destroy"]);

        Route::get("/select", [TeacherController::class, "select"]);
    });

    Route::get("/grade/select", function () {
        $grades = MasterGrade::get()->map(function ($query) {
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
        $years = MasterYear::get()->map(function ($query) {
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

    Route::get("/top/attend", [TopController::class, "attend"]);
});

Route::post("/login", [LoginController::class, "index"]);
