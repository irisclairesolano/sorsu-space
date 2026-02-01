<?php

namespace App\Http\Controllers;

use App\Http\Requests\PomodoroStartRequest;
use App\Http\Requests\PomodoroStopRequest;
use App\Models\PomodoroSession;
use Illuminate\Http\Request;

class PomodoroController extends Controller
{
    public function start(PomodoroStartRequest $request)
    {
        $session = PomodoroSession::query()->create([
            'user_id' => $request->user()->id,
            'started_at' => now(),
            'duration_minutes' => $request->validated()['duration_minutes'],
        ]);

        return response()->json($session, 201);
    }

    public function stop(PomodoroStopRequest $request)
    {
        $session = PomodoroSession::query()->where('id', $request->validated()['session_id'])
            ->where('user_id', $request->user()->id)
            ->firstOrFail();

        $session->update(['ended_at' => now()]);

        return response()->json($session);
    }

    public function history(Request $request)
    {
        return response()->json(PomodoroSession::query()->where('user_id', $request->user()->id)->latest()->get());
    }
}
