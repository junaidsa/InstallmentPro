<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Session;

class Notification extends Model
{
    use HasFactory;

    const ALL = 'All';
    const BOOKING = 'Booking';
    const PURCHASE = 'Purchase';
    const INSTALLMENT = 'Installment';

    protected $fillable = [
        'group_id',
        'title',
        'message',
        'url',
        'module',
        'params',
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
    public function receivers()
    {
        return $this->hasMany(NotificationReceiver::class);
    }
}
