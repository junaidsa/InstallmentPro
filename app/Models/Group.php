<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Session;

class Group extends Model
{
    use HasFactory;

    const SUPER_ADMIN = 'Super Admin';
    protected $fillable = [
        'name',
        'contact',
        'city',
        'address',
        'status',
        'logo',
        'company_type',
        'settings',
        'trial_expiry',
        'fee',
        'billing_cycle',
        'paid_till',
        'next_payments',
        'contact_2',
        'contact_3',
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
}
