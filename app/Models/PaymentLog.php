<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Session;

class PaymentLog extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'group_id',
        'station_id',
        'account_id',
        'installment_id',
        'transaction_id',
        'amount',
        'payment_mode',
        'is_approve',
        'sale_recovery_id',
    ];
    const APPROVE = 1;
    const PENDING = 0;
    const NOT_APPROVED = 2;
    const CASH = 'Cash';
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
    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }
    public function account()
    {
        return $this->belongsTo(Account::class);
    }
    public function recoveryman()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    public function installment()
    {
        return $this->belongsTo(Installment::class, 'installment_id');
    }
}
