<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Session;

class CashOut extends Model
{
    use HasFactory;
    protected $fillable = [
        'group_id',
        'account_id',
        'amount',
        'type',
        'cashout_date',
        'remarks',
        'narration',
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


    const TYPE_CASH_OUT = 'CashOut';
    const TYPE_SHOP_PROFIT = 'ShopProfit';
}
