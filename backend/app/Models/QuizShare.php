<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuizShare extends Model
{
    use HasFactory;

    protected $fillable = [
        'quiz_id',
        'scope_type',
        'scope_id',
    ];
}
