<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AiNote extends Model
{
    use HasFactory;

    protected $fillable = [
        'material_id',
        'user_id',
        'payload',
    ];

    protected $casts = [
        'payload' => 'array',
    ];
}
