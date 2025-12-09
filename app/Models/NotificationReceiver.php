<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Session;

class NotificationReceiver extends Model
{
    use HasFactory;
    protected $fillable = [
        'group_id',
        'notification_id',
        'user_id',
        'is_read',
    ];
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->created_by = auth()->check() ? auth()->id() : 0;
        });
        static::deleting(function ($model) {
            $model->deleted_by = Session::get('user')->id;
            $model->save();
        });
    }
    public function notification()
    {
        return $this->belongsTo(Notification::class);
    }
}
