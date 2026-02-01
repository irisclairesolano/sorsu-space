<?php

namespace App\Http\Controllers;

use App\Http\Requests\QuizAttemptRequest;
use App\Models\Quiz;
use App\Models\QuizAttempt;
use App\Models\QuizAttemptItem;
use App\Models\QuizQuestion;
use App\Models\QuizShare;
use Illuminate\Http\Request;

class QuizController extends Controller
{
    public function index(Request $request, int $material)
    {
        return response()->json(Quiz::query()->where('material_id', $material)->get());
    }

    public function show(Quiz $quiz)
    {
        $this->authorize('view', $quiz);

        return response()->json($quiz->load('questions'));
    }

    public function share(Request $request, Quiz $quiz)
    {
        $quiz->shares()->create($request->validate([
            'scope_type' => ['required', 'in:subject,user'],
            'scope_id' => ['required', 'integer'],
        ]));

        return response()->json(['message' => 'Shared.']);
    }

    public function attempt(QuizAttemptRequest $request, Quiz $quiz)
    {
        $this->authorize('view', $quiz);

        $questions = QuizQuestion::query()->where('quiz_id', $quiz->id)->get();
        $answers = $request->validated()['answers'];

        $score = 0;
        $attempt = QuizAttempt::query()->create([
            'quiz_id' => $quiz->id,
            'user_id' => $request->user()->id,
            'score' => 0,
            'total' => $questions->count(),
            'time_spent' => $request->validated()['time_spent'],
            'taken_at' => now(),
        ]);

        foreach ($questions as $question) {
            $given = $answers[$question->id] ?? null;
            $isCorrect = $given !== null && $given === $question->correct_answer;
            if ($isCorrect) {
                $score++;
            }

            QuizAttemptItem::query()->create([
                'quiz_attempt_id' => $attempt->id,
                'quiz_question_id' => $question->id,
                'given_answer' => $given,
                'is_correct' => $isCorrect,
            ]);
        }

        $attempt->update(['score' => $score]);

        return response()->json($attempt);
    }

    public function attempts(Request $request, Quiz $quiz)
    {
        return response()->json(QuizAttempt::query()
            ->where('quiz_id', $quiz->id)
            ->where('user_id', $request->user()->id)
            ->latest()
            ->get());
    }

    public function attemptById(QuizAttempt $attempt)
    {
        return response()->json($attempt->load('items'));
    }

    public function retryWrong(Request $request, QuizAttempt $attempt)
    {
        $wrongItems = QuizAttemptItem::query()
            ->where('quiz_attempt_id', $attempt->id)
            ->where('is_correct', false)
            ->pluck('quiz_question_id');

        $questions = QuizQuestion::query()->whereIn('id', $wrongItems)->get();

        return response()->json(['questions' => $questions]);
    }
}
