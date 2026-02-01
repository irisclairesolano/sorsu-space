<?php

namespace App\Policies;

use App\Models\Subject;
use App\Models\User;
use App\Services\AccessResolver;

class SubjectPolicy
{
    public function view(User $user, Subject $subject): bool
    {
        return app(AccessResolver::class)->isSubjectMember($user, $subject);
    }

    public function manage(User $user): bool
    {
        return $user->role === 'admin';
    }
}
