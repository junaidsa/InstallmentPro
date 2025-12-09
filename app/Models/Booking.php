<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Session;

class Booking extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'group_id',
        'station_id',
        'account_id',
        'recovery_man_id',
        'name',
        'block_id',
        'block_name',
        'property_type',
        'property_size',
        'purchase_id',
        'product_id',
        'deal_date',
        'total_amount',
        'discount_amount',
        'total_payable',
        'down_payment',
        'remaining_amount',
        'total_months',
        'late_payment_penalty',
        'monthly_installment',
        'status',
        'imei_no',
        'warranty_period',
        'warranty_expiry',
        'due_date',
    ];

    const BOOKING = 'BOOKING';
    public function getDealDateFormattedAttribute()
    {
        return $this->deal_date
            ? Carbon::parse($this->deal_date)->format('d-m-Y')
            : null;
    }
    public function recoveryMan()
    {
        return $this->belongsTo(Account::class, 'recovery_man_id');
    }
    public function getWarrantyExpiryFormattedAttribute()
    {
        return $this->warranty_expiry ? Carbon::parse($this->warranty_expiry)->format('j-n-Y') : null;
    }

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
    public function purchase()
    {
        return $this->belongsTo(Purchase::class, 'purchase_id', 'id');
    }

    public function account()
    {
        return $this->belongsTo(Account::class);
    }
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
    public function installments()
    {
        return $this->hasMany(Installment::class);
    }
    public function getWarrantyInfoAttribute()
    {
        if ($this->warranty_period && $this->warranty_expiry) {
            return "{$this->warranty_period} months (till {$this->warranty_expiry})";
        }
        return 'No Warranty';
    }
    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }
}
