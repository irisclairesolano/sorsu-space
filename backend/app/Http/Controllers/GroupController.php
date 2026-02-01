<?php

namespace App\Http\Controllers;

use App\Http\Requests\GroupInviteRequest;
use App\Http\Requests\GroupStoreRequest;
use App\Models\GroupMembership;
use App\Models\StudyGroup;
use App\Models\SubjectMembership;
use Illuminate\Http\Request;

class GroupController extends Controller
{
    public function index(Request $request, int $subject)
    {
        return response()->json(StudyGroup::query()->where('subject_id', $subject)->get());
    }

    public function store(GroupStoreRequest $request, int $subject)
    {
        $membership = SubjectMembership::query()
            ->where('user_id', $request->user()->id)
            ->where('subject_id', $subject)
            ->exists();

        if (! $membership) {
            return response()->json(['message' => 'Not a subject member.'], 403);
        }

        $group = StudyGroup::query()->create([
            'subject_id' => $subject,
            'name' => $request->validated()['name'],
            'description' => $request->validated()['description'] ?? null,
            'created_by' => $request->user()->id,
        ]);

        GroupMembership::query()->create([
            'study_group_id' => $group->id,
            'user_id' => $request->user()->id,
            'role' => 'owner',
        ]);

        return response()->json($group, 201);
    }

    public function invite(GroupInviteRequest $request, StudyGroup $group)
    {
        $this->authorize('manage', $group);

        $subjectMember = SubjectMembership::query()
            ->where('user_id', $request->validated()['user_id'])
            ->where('subject_id', $group->subject_id)
            ->exists();

        if (! $subjectMember) {
            return response()->json(['message' => 'User is not a subject member.'], 422);
        }

        GroupMembership::query()->firstOrCreate([
            'study_group_id' => $group->id,
            'user_id' => $request->validated()['user_id'],
        ], [
            'role' => 'member',
            'invited_by' => $request->user()->id,
        ]);

        return response()->json(['message' => 'Invitation added.']);
    }

    public function join(Request $request, StudyGroup $group)
    {
        $subjectMember = SubjectMembership::query()
            ->where('user_id', $request->user()->id)
            ->where('subject_id', $group->subject_id)
            ->exists();

        if (! $subjectMember) {
            return response()->json(['message' => 'Not a subject member.'], 403);
        }

        GroupMembership::query()->firstOrCreate([
            'study_group_id' => $group->id,
            'user_id' => $request->user()->id,
        ], ['role' => 'member']);

        return response()->json(['message' => 'Joined group.']);
    }

    public function leave(Request $request, StudyGroup $group)
    {
        GroupMembership::query()
            ->where('study_group_id', $group->id)
            ->where('user_id', $request->user()->id)
            ->delete();

        return response()->json(['message' => 'Left group.']);
    }
}
