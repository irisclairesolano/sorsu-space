<?php

namespace App\Http\Controllers;

use App\Http\Requests\SubjectStoreRequest;
use App\Http\Requests\SubjectUpdateRequest;
use App\Models\Subject;
use App\Models\SubjectMembership;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    public function index(Request $request)
    {
        $query = Subject::query()->where('active', true);

        if ($search = $request->query('q')) {
            $query->where('title', 'ilike', "%{$search}%");
        }

        return response()->json($query->paginate());
    }

    public function store(SubjectStoreRequest $request)
    {
        $subject = Subject::query()->create($request->validated());

        return response()->json($subject, 201);
    }

    public function show(Subject $subject)
    {
        $this->authorize('view', $subject);

        return response()->json($subject);
    }

    public function update(SubjectUpdateRequest $request, Subject $subject)
    {
        $subject->update($request->validated());

        return response()->json($subject);
    }

    public function join(Request $request, Subject $subject)
    {
        SubjectMembership::query()->firstOrCreate([
            'user_id' => $request->user()->id,
            'subject_id' => $subject->id,
        ], ['role' => 'member']);

        return response()->json(['message' => 'Joined subject.']);
    }

    public function leave(Request $request, Subject $subject)
    {
        SubjectMembership::query()
            ->where('user_id', $request->user()->id)
            ->where('subject_id', $subject->id)
            ->delete();

        return response()->json(['message' => 'Left subject.']);
    }
}
