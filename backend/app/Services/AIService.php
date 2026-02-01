<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class AIService
{
    public function generateJson(string $prompt): array
    {
        $content = $this->requestCompletion($prompt);

        try {
            return AiJsonParser::parse($content);
        } catch (\Throwable $exception) {
            $fixPrompt = json_encode([
                'instruction' => 'Fix JSON only.',
                'input' => $content,
            ]);

            $fixedContent = $this->requestCompletion($fixPrompt);

            return AiJsonParser::parse($fixedContent);
        }
    }

    private function requestCompletion(string $prompt): string
    {
        $response = Http::withToken(config('services.openai.key'))
            ->post('https://api.openai.com/v1/chat/completions', [
                'model' => config('sorsu.ai.model'),
                'temperature' => config('sorsu.ai.temperature'),
                'messages' => [
                    ['role' => 'system', 'content' => 'You are a JSON-only assistant.'],
                    ['role' => 'user', 'content' => $prompt],
                ],
            ]);

        return $response->json('choices.0.message.content', '');
    }
}
