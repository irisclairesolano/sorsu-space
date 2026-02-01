<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AiGenerateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'material_id' => ['sometimes', 'integer'],
        ];
    }
}
