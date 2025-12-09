<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Session;

class Transaction extends Model
{

    protected $fillable = [
        'group_id',
        'station_id',
        'account_id',
        'booking_id',
        'installment_id',
        'amount',
        'payment_mode',
        'type',
        'balance',
        'remarks',
        'doc_no',
        'narration',
        'image',
    ];
    const DOWN_PAYMENT = "Down Payment";
    const TYPE_CREDIT     = 'Credit';
    const TYPE_DEBIT     = 'Debit';
    const MONTHLY_SHOP_PROFIT_REMARK = 'Monthly Shop Profit';
    const INVESTMENT_REMARKS = 'Investment Added';
    const PURCHASE_REMARKS = 'Purchase Added';
    const ALL = 'All';
    const TRANSACTION_MEDIA = 'transaction-images';

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
    public function booking()
    {
        return $this->belongsTo(Booking::class, 'booking_id');
    }
    public function installment()
    {
        return $this->belongsTo(Installment::class, 'installment_id');
    }
    public function paymentLog()
    {
        return $this->hasOne(PaymentLog::class);
    }
}
