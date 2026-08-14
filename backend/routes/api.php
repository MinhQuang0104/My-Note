<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CalendarController;
use App\Http\Controllers\Api\GoalController;
use App\Http\Controllers\Api\GoalEntryController;
use App\Http\Controllers\Api\NoteController;
use Illuminate\Support\Facades\Route;

Route::get('/health', function () {
    return response()->json(['status' => 'ok']);
});

Route::post('/auth/register', [AuthController::class, 'register']);
Route::post('/auth/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/auth/logout', [AuthController::class, 'logout']);
    Route::get('/auth/me', [AuthController::class, 'me']);

    Route::apiResource('/notes', NoteController::class);
    Route::apiResource('/goals', GoalController::class);
    Route::apiResource('/goals.entries', GoalEntryController::class)->parameters([
        'goals' => 'goal',
        'entries' => 'goalEntry',
    ]);
    Route::get('/calendar', [CalendarController::class, 'index']);
});
