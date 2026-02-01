<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MaterialStoreRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'subject_id' => ['required', 'integer', 'exists:subjects,id'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'visibility' => ['required', 'in:subject,group,individual,private'],
            'tags' => ['array'],
            'tags.*' => ['string', 'max:50'],
            'file' => ['required', 'file', 'max:10240'],
        ];
    }
}
