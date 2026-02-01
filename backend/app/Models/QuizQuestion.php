<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuizQuestion extends Model
{
    use HasFactory;

    protected $fillable = [
        'quiz_id',
        'type',
        'question',
        'choices',
        'correct_answer',
        'explanation',
    ];

    protected $casts = [
        'choices' => 'array',
    ];
}
