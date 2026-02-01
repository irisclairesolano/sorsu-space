<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaterialShare extends Model
{
    use HasFactory;

    protected $fillable = [
        'material_id',
        'scope_type',
        'scope_id',
    ];
}
