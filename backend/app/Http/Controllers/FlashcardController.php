<?php

namespace App\Http\Controllers;

use App\Models\Flashcard;
use App\Models\Material;
use Illuminate\Http\Request;

class FlashcardController extends Controller
{
    public function index(Request $request, Material $material)
    {
        $this->authorize('view', $material);

        return response()->json(Flashcard::query()->where('material_id', $material->id)->get());
    }

    public function update(Request $request, Flashcard $flashcard)
    {
        $flashcard->update($request->validate([
            'question' => ['sometimes', 'string'],
            'answer' => ['sometimes', 'string'],
            'difficulty' => ['sometimes', 'in:easy,medium,hard'],
            'topic' => ['nullable', 'string'],
        ]));

        return response()->json($flashcard);
    }

    public function destroy(Flashcard $flashcard)
    {
        $flashcard->delete();

        return response()->json(['message' => 'Deleted.']);
    }
}
