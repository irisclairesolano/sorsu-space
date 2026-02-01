<?php

namespace App\Http\Controllers;

use App\Http\Requests\AiGenerateRequest;
use App\Jobs\GenerateFlashcardsJob;
use App\Jobs\GenerateNotesJob;
use App\Jobs\GenerateQuizJob;
use App\Models\AiJob;
use App\Models\Material;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;

class AiController extends Controller
{
    public function notes(AiGenerateRequest $request, Material $material)
    {
        return $this->dispatchJob($request, $material, 'notes', GenerateNotesJob::class);
    }

    public function flashcards(AiGenerateRequest $request, Material $material)
    {
        return $this->dispatchJob($request, $material, 'flashcards', GenerateFlashcardsJob::class);
    }

    public function quiz(AiGenerateRequest $request, Material $material)
    {
        return $this->dispatchJob($request, $material, 'quiz', GenerateQuizJob::class);
    }

    public function status(AiJob $aiJob)
    {
        return response()->json($aiJob);
    }

    private function dispatchJob(Request $request, Material $material, string $type, string $jobClass)
    {
        $this->authorize('view', $material);

        $key = sprintf('ai:%s:%s', $request->user()->id, now()->toDateString());
        if (RateLimiter::tooManyAttempts($key, config('sorsu.ai.daily_limit'))) {
            return response()->json(['message' => 'Daily AI limit reached.'], 429);
        }

        RateLimiter::hit($key);

        $job = AiJob::query()->create([
            'user_id' => $request->user()->id,
            'material_id' => $material->id,
            'type' => $type,
            'status' => 'pending',
        ]);

        dispatch(new $jobClass($job->id));

        return response()->json([
            'job_id' => $job->id,
            'status' => $job->status,
        ], 202);
    }
}
