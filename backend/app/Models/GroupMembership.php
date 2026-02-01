<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GroupMembership extends Model
{
    use HasFactory;

    protected $fillable = [
        'study_group_id',
        'user_id',
        'role',
        'invited_by',
    ];
}
