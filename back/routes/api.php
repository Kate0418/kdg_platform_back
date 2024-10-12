<?php

use App\Http\Controllers\LoginController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\CourseController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/token', function () {
        return response()->json(["success" => true]);
    });

    Route::prefix('student')->group(function () {
        Route::post('/get_users', [StudentController::class, 'get_users']);
        Route::post('/add', [StudentController::class, 'add']);
    });

    Route::post('/course/select', [CourseController::class, 'select']);
});

Route::post('/login', [LoginController::class, 'index']);