<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UsersScreenArrangement extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'screen_id',
        'group_id',
        'sequence',
        'updated_by',
        'created_by',
    ];
}
