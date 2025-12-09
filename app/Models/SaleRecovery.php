<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Session;

class SaleRecovery extends Model
{
    use HasFactory;
    protected $fillable = [
        'group_id',
        'recovery_man_id',
        'amount',
        'remaining_amount',
        'total_amount',
        'payment_method',
        'remarks',
        'status',
        'approved_by',
        'approved_at',
        'created_by',
        'updated_by',
        'deleted_by',
    ];
    const APPROVE = 1;
    const STATUS_COMPLETED = 'completed';
    const STATUS_PENDING = 'pending';
    const PAYMENT_METHOD_CASH = 'cash';
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
        'approved_at' => 'datetime',
    ];

    public function recoveryMan()
    {
        return $this->belongsTo(Account::class, 'recovery_man_id');
    }

    public function installment()
    {
        return $this->belongsTo(Installment::class);
    }
}
