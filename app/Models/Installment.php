<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Session;

class Installment extends Model
{
    use HasFactory, SoftDeletes;
    public $timestamps = false;
    protected $fillable = [
        'group_id',
        'station_id',
        'account_id',
        'booking_id',
        'installment_id',
        'installment_title',
        'month',
        'year',
        'amount',
        'paid_amount',
        'remaining_amount',
        'due_date',
        'status',
        'remarks',
        'late_payment_penalty',
        'image',
        'is_approve',
        'sale_recovery_id',
        'created_at',
        'updated_at',
    ];
    public function getDueDateFormattedAttribute()
    {
        return $this->due_date
            ? Carbon::parse($this->due_date)->format('d-m-Y')
            : null;
    }


    const STATUS_FULL_PAY = 'Full Pay';
    const STATUS_PENDING = 'Pending';
    const STATUS_DOWN_PAYMENT = 'Down Payment';
    const STATUS_PARTIAL_PAY = 'Partially Paid';
    const ALL = 'All';
    public const PROOF_UPLOAD_PATH = 'proofs';

    const STATUSES = [
        self::STATUS_PENDING,
        self::STATUS_PARTIAL_PAY,
        self::STATUS_FULL_PAY,
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
        return $this->belongsTo(Account::class);
    }
    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
    public function createBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    public function paymentLogs()
    {
        return $this->hasMany(PaymentLog::class);
    }

    public function latestPaymentLog()
    {
        return $this->hasOne(PaymentLog::class, 'installment_id')->latestOfMany();
    }
}
