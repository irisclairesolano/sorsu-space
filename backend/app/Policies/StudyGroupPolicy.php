<?php

namespace App\Policies;

use App\Models\StudyGroup;
use App\Models\User;
use App\Services\AccessResolver;

class StudyGroupPolicy
{
    public function view(User $user, StudyGroup $group): bool
    {
        return app(AccessResolver::class)->isGroupMember($user, $group->id);
    }

    public function manage(User $user, StudyGroup $group): bool
    {
        return $group->created_by === $user->id;
    }
}
