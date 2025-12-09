<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Session;

class Purchase extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'group_id',
        'account_id',
        'product_type',
        'product_id',
        'station_id',
        'cost_price',
        'sale_price',
        'total_price',
        'remarks',
        'purchase_date',
        'quantity',
        'quantity_log'
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

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    const STOCK_TYPE = [
        'All',
        'Sold',
        'Available',
    ];

    public static function types(): array
    {
        return self::STOCK_TYPE;
    }
}
