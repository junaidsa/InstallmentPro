<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Session;

class Blacklist extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'account_id',
        'group_id',
        'station_id',
        'name',
        'cnic',
        'contact',
        'address',
        'notes',
    ];

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

    public function account()
    {
        return $this->belongsTo(Account::class, 'account_id');
    }
}
