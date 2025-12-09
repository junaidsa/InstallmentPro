<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Guarantor extends Model
{
    use HasFactory;
    protected $fillable = [
        'group_id',
        'customer_id',
        'group_id',
        'name',
        'father_name',
        'address',
        'phone',
        'cnic',
        'cnic_media',
    ];
}
