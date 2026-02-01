<?php

namespace App\Jobs;

use App\Models\AiJob;
use App\Models\AiNote;
use App\Models\Material;
use App\Services\AiPromptBuilder;
use App\Services\AIService;
use App\Services\ExtractTextService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class GenerateNotesJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public function __construct(public int $aiJobId)
    {
    }

    public function handle(AIService $service, ExtractTextService $extractor): void
    {
        $job = AiJob::query()->findOrFail($this->aiJobId);
        $material = Material::query()->findOrFail($job->material_id);

        $job->update(['status' => 'running']);

        try {
            $source = $extractor->extract($material->description ?? $material->title);
            $payload = $service->generateJson(AiPromptBuilder::notes($material, $source));

            AiNote::query()->create([
                'material_id' => $material->id,
                'user_id' => $job->user_id,
                'payload' => $payload,
            ]);

            $job->update(['status' => 'succeeded', 'payload' => $payload]);
        } catch (\Throwable $exception) {
            $job->update([
                'status' => 'failed',
                'error_message' => $exception->getMessage(),
            ]);
        }
    }
}
