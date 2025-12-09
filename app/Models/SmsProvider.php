<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Session;

class SmsProvider extends Model
{
    use HasFactory;
    protected $fillable = [
        'group_id',
        'name',
        'base_url',
        'method',
        'params',
        'headers',
        'active',
    ];
    const METHOD_GET = 'GET';
    const METHOD_POST = 'POST';
    const ACTIVE = 1;

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->created_by = Session::get('user')->id;
        });
        static::updating(function ($model) {
            $model->updated_by = Session::get('user')->id;
        });
        static::deleting(function ($model) {
            $model->deleted_by = Session::get('user')->id;
            $model->save();
        });
    }
}
