<?php

namespace App\Http\Controllers;

use App\Http\Requests\CommentStoreRequest;
use App\Http\Requests\MaterialShareRequest;
use App\Http\Requests\MaterialStoreRequest;
use App\Http\Requests\MaterialUpdateRequest;
use App\Http\Requests\ReactionStoreRequest;
use App\Models\Material;
use App\Models\MaterialComment;
use App\Models\MaterialReaction;
use App\Models\MaterialShare;
use App\Models\MaterialTag;
use App\Models\SubjectMembership;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MaterialController extends Controller
{
    public function index(Request $request)
    {
        $query = Material::query()->where('user_id', $request->user()->id);

        if ($subjectId = $request->query('subject_id')) {
            $query->where('subject_id', $subjectId);
        }

        if ($search = $request->query('q')) {
            $query->where('title', 'ilike', "%{$search}%");
        }

        return response()->json($query->paginate());
    }

    public function store(MaterialStoreRequest $request)
    {
        $membership = SubjectMembership::query()
            ->where('user_id', $request->user()->id)
            ->where('subject_id', $request->validated()['subject_id'])
            ->exists();

        if (! $membership) {
            return response()->json(['message' => 'Not a subject member.'], 403);
        }

        $file = $request->file('file');
        $path = $file->store('materials', ['disk' => config('filesystems.default')]);

        $material = Material::query()->create([
            'user_id' => $request->user()->id,
            'subject_id' => $request->validated()['subject_id'],
            'title' => $request->validated()['title'],
            'description' => $request->validated()['description'] ?? null,
            'visibility' => $request->validated()['visibility'],
            'file_path' => $path,
            'file_disk' => config('filesystems.default'),
        ]);

        foreach ($request->validated()['tags'] ?? [] as $tag) {
            MaterialTag::query()->create([
                'material_id' => $material->id,
                'tag' => Str::lower($tag),
            ]);
        }

        return response()->json($material, 201);
    }

    public function show(Material $material)
    {
        $this->authorize('view', $material);

        return response()->json($material);
    }

    public function update(MaterialUpdateRequest $request, Material $material)
    {
        $this->authorize('update', $material);

        $material->update($request->validated());

        return response()->json($material);
    }

    public function destroy(Material $material)
    {
        $this->authorize('delete', $material);

        $material->delete();

        return response()->json(['message' => 'Deleted.']);
    }

    public function share(MaterialShareRequest $request, Material $material)
    {
        $this->authorize('update', $material);

        MaterialShare::query()->create([
            'material_id' => $material->id,
            'scope_type' => $request->validated()['scope_type'],
            'scope_id' => $request->validated()['scope_id'],
        ]);

        return response()->json(['message' => 'Shared.']);
    }

    public function download(Request $request, Material $material)
    {
        $this->authorize('view', $material);

        return Storage::disk($material->file_disk)->download($material->file_path);
    }

    public function comment(CommentStoreRequest $request, Material $material)
    {
        $this->authorize('view', $material);

        $cleanBody = strip_tags($request->validated()['body']);

        $comment = MaterialComment::query()->create([
            'material_id' => $material->id,
            'user_id' => $request->user()->id,
            'body' => $cleanBody,
        ]);

        return response()->json($comment, 201);
    }

    public function comments(Material $material)
    {
        $this->authorize('view', $material);

        return response()->json(MaterialComment::query()->where('material_id', $material->id)->latest()->get());
    }

    public function react(ReactionStoreRequest $request, Material $material)
    {
        $this->authorize('view', $material);

        MaterialReaction::query()->updateOrCreate([
            'material_id' => $material->id,
            'user_id' => $request->user()->id,
        ], [
            'reaction' => $request->validated()['reaction'],
        ]);

        return response()->json(['message' => 'Reaction saved.']);
    }
}
