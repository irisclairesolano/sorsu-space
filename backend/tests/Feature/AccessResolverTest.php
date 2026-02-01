<?php

use App\Models\GroupMembership;
use App\Models\Material;
use App\Models\MaterialShare;
use App\Models\Quiz;
use App\Models\QuizShare;
use App\Models\StudyGroup;
use App\Models\Subject;
use App\Models\SubjectMembership;
use App\Models\User;
use App\Services\AccessResolver;

it('resolves subject and group membership access', function () {
    $user = User::query()->create([
        'name' => 'Student',
        'email' => 'student@sorsu.edu.ph',
        'password' => 'secret',
    ]);

    $subject = Subject::query()->create([
        'code' => 'CS201',
        'title' => 'Data Structures',
    ]);

    SubjectMembership::query()->create([
        'user_id' => $user->id,
        'subject_id' => $subject->id,
        'role' => 'member',
    ]);

    $group = StudyGroup::query()->create([
        'subject_id' => $subject->id,
        'name' => 'Section A',
        'created_by' => $user->id,
    ]);

    GroupMembership::query()->create([
        'study_group_id' => $group->id,
        'user_id' => $user->id,
        'role' => 'member',
    ]);

    $resolver = app(AccessResolver::class);

    expect($resolver->isSubjectMember($user, $subject))->toBeTrue();
    expect($resolver->isGroupMember($user, $group->id))->toBeTrue();
});

it('authorizes material and quiz shares', function () {
    $user = User::query()->create([
        'name' => 'Student',
        'email' => 'member@sorsu.edu.ph',
        'password' => 'secret',
    ]);

    $subject = Subject::query()->create([
        'code' => 'BIO101',
        'title' => 'Biology',
    ]);

    SubjectMembership::query()->create([
        'user_id' => $user->id,
        'subject_id' => $subject->id,
        'role' => 'member',
    ]);

    $material = Material::query()->create([
        'user_id' => $user->id,
        'subject_id' => $subject->id,
        'title' => 'Cell Notes',
        'description' => 'Basics of cells',
        'file_path' => 'materials/cell.pdf',
        'file_disk' => 'local',
        'visibility' => 'individual',
    ]);

    MaterialShare::query()->create([
        'material_id' => $material->id,
        'scope_type' => 'user',
        'scope_id' => $user->id,
    ]);

    $quiz = Quiz::query()->create([
        'material_id' => $material->id,
        'user_id' => $user->id,
        'title' => 'Cell Quiz',
    ]);

    QuizShare::query()->create([
        'quiz_id' => $quiz->id,
        'scope_type' => 'subject',
        'scope_id' => $subject->id,
    ]);

    $resolver = app(AccessResolver::class);

    expect($resolver->canAccessMaterial($user, $material))->toBeTrue();
    expect($resolver->canAccessQuiz($user, $quiz))->toBeTrue();
});
