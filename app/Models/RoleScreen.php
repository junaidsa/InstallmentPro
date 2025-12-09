<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Session;

class RoleScreen extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'group_id',
        'role_id',
        'screen_id',
        'company_id',
        'expiry_date',
        'can_write',

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

    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    public function softwareScreen()
    {
        return $this->belongsTo(SoftwareScreen::class, 'screen_id');
    }
}
