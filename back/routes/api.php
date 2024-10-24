<?php

use App\Http\Controllers\LoginController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\TeacherController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/token', function () {
        return response()->json(["success" => true]);
    });

    Route::prefix('student')->group(function () {
        Route::post('/get_users', [StudentController::class, 'get_users']);
        Route::post('/add', [StudentController::class, 'add']);
    });

    Route::prefix('subject')->group(function () {
        Route::post('/get', [SubjectController::class, 'get']);
        Route::post('/add', [SubjectController::class, 'add']);
        Route::post('/select', [SubjectController::class, 'select']);
    });

    Route::post('/course/select', [CourseController::class, 'select']);

    Route::prefix('teacher')->group(function () {
        Route::post('/add', [TeacherController::class, 'add']);
        Route::get('/select', [TeacherController::class, 'select']);
    });
});

Route::post('/login', [LoginController::class, 'index']);