<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaterialComment extends Model
{
    use HasFactory;

    protected $fillable = [
        'material_id',
        'user_id',
        'body',
    ];
}
