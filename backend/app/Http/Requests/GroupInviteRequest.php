<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GroupInviteRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'user_id' => ['required', 'integer', 'exists:users,id'],
        ];
    }
}
