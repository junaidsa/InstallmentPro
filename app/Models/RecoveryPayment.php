<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Session;

class RecoveryPayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'group_id',
        'sale_recovery_id',
        'recovery_man_id',
        'amount',
        'deleted_by',
        'created_at',
        'updated_at',
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

    protected $casts = [
        'given_amount' => 'decimal:2',
        'remaining_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
    ];

    public function saleRecovery()
    {
        return $this->belongsTo(SaleRecovery::class);
    }

    public function recoveryMan()
    {
        return $this->belongsTo(Account::class, 'recovery_man_id');
    }
}
