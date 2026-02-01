<?php

namespace App\Services;

use App\Models\GroupMembership;
use App\Models\Material;
use App\Models\MaterialShare;
use App\Models\Quiz;
use App\Models\QuizShare;
use App\Models\Subject;
use App\Models\SubjectMembership;
use App\Models\User;

class AccessResolver
{
    public function isSubjectMember(User $user, Subject $subject): bool
    {
        return SubjectMembership::query()
            ->where('user_id', $user->id)
            ->where('subject_id', $subject->id)
            ->exists();
    }

    public function isGroupMember(User $user, int $groupId): bool
    {
        return GroupMembership::query()
            ->where('user_id', $user->id)
            ->where('study_group_id', $groupId)
            ->exists();
    }

    public function canAccessMaterial(User $user, Material $material): bool
    {
        if (! $this->isSubjectMember($user, $material->subject)) {
            return false;
        }

        return match ($material->visibility) {
            'subject' => true,
            'group' => MaterialShare::query()
                ->where('material_id', $material->id)
                ->where('scope_type', 'group')
                ->whereIn('scope_id', GroupMembership::query()
                    ->where('user_id', $user->id)
                    ->pluck('study_group_id'))
                ->exists(),
            'individual' => MaterialShare::query()
                ->where('material_id', $material->id)
                ->where('scope_type', 'user')
                ->where('scope_id', $user->id)
                ->exists(),
            default => $material->user_id === $user->id,
        };
    }

    public function canAccessQuiz(User $user, Quiz $quiz): bool
    {
        if (! $quiz->material) {
            return false;
        }

        if (! $this->isSubjectMember($user, $quiz->material->subject)) {
            return false;
        }

        return QuizShare::query()
            ->where('quiz_id', $quiz->id)
            ->where(function ($query) use ($user) {
                $query->where('scope_type', 'subject')
                    ->orWhere(function ($q) use ($user) {
                        $q->where('scope_type', 'user')
                            ->where('scope_id', $user->id);
                    });
            })
            ->exists();
    }
}
