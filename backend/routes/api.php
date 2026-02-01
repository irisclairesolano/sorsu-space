<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\MaterialController;
use App\Http\Controllers\AiController;
use App\Http\Controllers\FlashcardController;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\PomodoroController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\CalendarEventController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\DashboardController;

Route::prefix('auth')->group(function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
    Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
    Route::post('verify-email', [AuthController::class, 'verifyEmail']);
    Route::get('me', [AuthController::class, 'me'])->middleware('auth:sanctum');
});

Route::middleware(['auth:sanctum', 'verified'])->group(function () {
    Route::get('subjects', [SubjectController::class, 'index']);
    Route::post('subjects', [SubjectController::class, 'store'])->middleware('can:admin');
    Route::get('subjects/{subject}', [SubjectController::class, 'show']);
    Route::patch('subjects/{subject}', [SubjectController::class, 'update'])->middleware('can:admin');
    Route::post('subjects/{subject}/join', [SubjectController::class, 'join']);
    Route::post('subjects/{subject}/leave', [SubjectController::class, 'leave']);

    Route::post('subjects/{subject}/groups', [GroupController::class, 'store']);
    Route::get('subjects/{subject}/groups', [GroupController::class, 'index']);
    Route::post('groups/{group}/invite', [GroupController::class, 'invite']);
    Route::post('groups/{group}/join', [GroupController::class, 'join']);
    Route::post('groups/{group}/leave', [GroupController::class, 'leave']);

    Route::post('materials', [MaterialController::class, 'store']);
    Route::get('materials', [MaterialController::class, 'index']);
    Route::get('materials/{material}', [MaterialController::class, 'show']);
    Route::patch('materials/{material}', [MaterialController::class, 'update']);
    Route::delete('materials/{material}', [MaterialController::class, 'destroy']);
    Route::post('materials/{material}/share', [MaterialController::class, 'share']);
    Route::get('materials/{material}/download', [MaterialController::class, 'download']);
    Route::post('materials/{material}/comments', [MaterialController::class, 'comment']);
    Route::get('materials/{material}/comments', [MaterialController::class, 'comments']);
    Route::post('materials/{material}/react', [MaterialController::class, 'react']);

    Route::post('ai/materials/{material}/generate-notes', [AiController::class, 'notes']);
    Route::post('ai/materials/{material}/generate-flashcards', [AiController::class, 'flashcards']);
    Route::post('ai/materials/{material}/generate-quiz', [AiController::class, 'quiz']);
    Route::get('ai/jobs/{aiJob}', [AiController::class, 'status']);

    Route::get('materials/{material}/flashcards', [FlashcardController::class, 'index']);
    Route::patch('flashcards/{flashcard}', [FlashcardController::class, 'update']);
    Route::delete('flashcards/{flashcard}', [FlashcardController::class, 'destroy']);

    Route::get('materials/{material}/quizzes', [QuizController::class, 'index']);
    Route::get('quizzes/{quiz}', [QuizController::class, 'show']);
    Route::post('quizzes/{quiz}/share', [QuizController::class, 'share']);
    Route::post('quizzes/{quiz}/attempt', [QuizController::class, 'attempt']);
    Route::get('quizzes/{quiz}/attempts/me', [QuizController::class, 'attempts']);
    Route::get('attempts/{attempt}', [QuizController::class, 'attemptById']);
    Route::post('attempts/{attempt}/retry-wrong', [QuizController::class, 'retryWrong']);

    Route::post('pomodoro/start', [PomodoroController::class, 'start']);
    Route::post('pomodoro/stop', [PomodoroController::class, 'stop']);
    Route::get('pomodoro/history', [PomodoroController::class, 'history']);

    Route::apiResource('tasks', TaskController::class);
    Route::apiResource('calendar-events', CalendarEventController::class);
    Route::get('calendar/school-events', [CalendarEventController::class, 'schoolEvents']);

    Route::post('reports', [ReportController::class, 'store']);
    Route::get('reports', [ReportController::class, 'index'])->middleware('can:moderate');
    Route::patch('reports/{report}/resolve', [ReportController::class, 'resolve'])->middleware('can:moderate');

    Route::get('dashboard/student', [DashboardController::class, 'student']);
    Route::get('dashboard/admin', [DashboardController::class, 'admin'])->middleware('can:admin');
});
