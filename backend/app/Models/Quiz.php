<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quiz extends Model
{
    use HasFactory;

    protected $fillable = [
        'material_id',
        'user_id',
        'title',
    ];

    public function material()
    {
        return $this->belongsTo(Material::class);
    }

    public function questions()
    {
        return $this->hasMany(QuizQuestion::class);
    }

    public function shares()
    {
        return $this->hasMany(QuizShare::class);
    }
}
