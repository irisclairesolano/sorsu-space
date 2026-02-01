<?php

namespace App\Policies;

use App\Models\Material;
use App\Models\User;
use App\Services\AccessResolver;

class MaterialPolicy
{
    public function view(User $user, Material $material): bool
    {
        return app(AccessResolver::class)->canAccessMaterial($user, $material);
    }

    public function update(User $user, Material $material): bool
    {
        return $material->user_id === $user->id;
    }

    public function delete(User $user, Material $material): bool
    {
        return $material->user_id === $user->id;
    }
}
