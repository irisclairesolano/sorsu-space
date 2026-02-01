<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MaterialShareRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'scope_type' => ['required', 'in:subject,group,user'],
            'scope_id' => ['required', 'integer'],
        ];
    }
}
