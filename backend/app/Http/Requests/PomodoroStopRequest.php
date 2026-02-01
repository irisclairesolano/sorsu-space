<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PomodoroStopRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'session_id' => ['required', 'integer', 'exists:pomodoro_sessions,id'],
        ];
    }
}
