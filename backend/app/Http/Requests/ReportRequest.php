<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReportRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'reportable_type' => ['required', 'string'],
            'reportable_id' => ['required', 'integer'],
            'reason' => ['required', 'string', 'max:500'],
        ];
    }
}
