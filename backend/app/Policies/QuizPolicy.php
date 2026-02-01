<?php

namespace App\Policies;

use App\Models\Quiz;
use App\Models\User;
use App\Services\AccessResolver;

class QuizPolicy
{
    public function view(User $user, Quiz $quiz): bool
    {
        return app(AccessResolver::class)->canAccessQuiz($user, $quiz);
    }
}
