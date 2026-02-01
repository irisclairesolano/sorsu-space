<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MaterialUpdateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'title' => ['sometimes', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'visibility' => ['sometimes', 'in:subject,group,individual,private'],
            'tags' => ['array'],
            'tags.*' => ['string', 'max:50'],
        ];
    }
}
