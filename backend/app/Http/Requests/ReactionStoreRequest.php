<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReactionStoreRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'reaction' => ['required', 'in:helpful'],
        ];
    }
}
