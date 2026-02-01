<?php

namespace App\Http\Controllers;

use App\Models\AiNote;
use App\Models\Material;
use App\Models\PomodoroSession;
use App\Models\QuizAttempt;
use App\Models\Report;
use App\Models\Subject;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function student(Request $request)
    {
        $userId = $request->user()->id;

        return response()->json([
            'tasks_due' => Task::query()->where('user_id', $userId)->whereNull('completed_at')->orderBy('due_at')->take(5)->get(),
            'study_time_minutes' => PomodoroSession::query()->where('user_id', $userId)->sum('duration_minutes'),
            'quiz_trends' => QuizAttempt::query()->where('user_id', $userId)->latest()->take(10)->get(),
            'weak_topics' => AiNote::query()->where('user_id', $userId)->latest()->take(5)->get(),
        ]);
    }

    public function admin()
    {
        return response()->json([
            'active_users' => User::query()->count(),
            'uploads' => Material::query()->count(),
            'reports' => Report::query()->where('status', 'open')->count(),
            'top_subjects' => Subject::query()->take(5)->get(),
        ]);
    }
}
