<?php

namespace App\Services;

use App\Models\Material;

class AiPromptBuilder
{
    public static function notes(Material $material, string $source): string
    {
        return json_encode([
            'task' => 'Generate study notes JSON for the provided material text.',
            'requirements' => 'Return JSON only matching the required schema. No prose.',
            'schema' => [
                'title' => 'string',
                'summary' => 'string',
                'key_points' => ['string'],
                'definitions' => [['term' => 'string', 'definition' => 'string']],
                'examples' => ['string'],
                'common_misconceptions' => ['string'],
                'practice_prompts' => ['string'],
            ],
            'material_title' => $material->title,
            'material_description' => $material->description,
            'material_text' => $source,
        ]);
    }

    public static function flashcards(Material $material, string $source): string
    {
        return json_encode([
            'task' => 'Generate flashcards JSON for the provided material text.',
            'requirements' => 'Return JSON only matching the required schema. No prose.',
            'schema' => [
                'deck_title' => 'string',
                'cards' => [[
                    'question' => 'string',
                    'answer' => 'string',
                    'difficulty' => 'easy|medium|hard',
                    'topic' => 'string',
                ]],
            ],
            'material_title' => $material->title,
            'material_description' => $material->description,
            'material_text' => $source,
        ]);
    }

    public static function quiz(Material $material, string $source): string
    {
        return json_encode([
            'task' => 'Generate a quiz JSON for the provided material text.',
            'requirements' => 'Return JSON only matching the required schema. No prose.',
            'schema' => [
                'title' => 'string',
                'questions' => [[
                    'type' => 'mcq|short',
                    'question' => 'string',
                    'choices' => ['string'],
                    'correct_answer' => 'string',
                    'explanation' => 'string',
                ]],
            ],
            'material_title' => $material->title,
            'material_description' => $material->description,
            'material_text' => $source,
        ]);
    }
}
