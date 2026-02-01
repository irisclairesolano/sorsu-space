<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PomodoroStartRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'duration_minutes' => ['required', 'integer', 'min:1', 'max:180'],
        ];
    }
}
