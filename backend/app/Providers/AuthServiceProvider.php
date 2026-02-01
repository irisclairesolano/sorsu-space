<?php

namespace App\Providers;

use App\Models\Material;
use App\Models\Quiz;
use App\Models\StudyGroup;
use App\Models\Subject;
use App\Policies\MaterialPolicy;
use App\Policies\QuizPolicy;
use App\Policies\StudyGroupPolicy;
use App\Policies\SubjectPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Subject::class => SubjectPolicy::class,
        StudyGroup::class => StudyGroupPolicy::class,
        Material::class => MaterialPolicy::class,
        Quiz::class => QuizPolicy::class,
    ];

    public function boot(): void
    {
        $this->registerPolicies();

        Gate::define('admin', fn ($user) => $user->role === 'admin');
        Gate::define('moderate', fn ($user) => in_array($user->role, ['admin', 'moderator'], true));
    }
}
