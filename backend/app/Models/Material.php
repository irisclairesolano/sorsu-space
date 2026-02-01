<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Subject;

class Material extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'subject_id',
        'title',
        'description',
        'file_path',
        'file_disk',
        'visibility',
    ];

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }
}
