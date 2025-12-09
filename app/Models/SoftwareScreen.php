<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Traits\HasPermissions;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Session;
use Laravel\Sanctum\HasApiTokens;

class SoftwareScreen extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes, HasPermissions;
    const PARENT = 1;

    const NOT_PARENT = 0;

    protected $fillable = [
        'group_id',
        'screen_name',
        'is_parent',
        'parent_id',
        'permission_key',
        'directory',

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

    public function screenTabs()
    {
        return $this->hasMany(ScreenTab::class, 'screen_id');
    }

    protected $casts = [
        'is_parent' => 'boolean',
    ];
}
