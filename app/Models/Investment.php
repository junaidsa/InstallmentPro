<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Session;

class Investment extends Model
{
    use HasFactory;
    protected $fillable = [
        'group_id',
        'account_id',
        'investor_name',
        'amount',
        'total_amount',
        'investment_date',
        'status',
        'created_by',
        'updated_by',
    ];
    const REINVESTED = 'ReInvested';
    const CASH_OUT = 'Cash Out';
    const ACTIVE = 'active';
    public function getInvestmentDateFormattedAttribute()
    {
        return $this->investment_date ? Carbon::parse($this->investment_date)->format('d-m-Y') : null;
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
    public function account()
    {
        return $this->belongsTo(Account::class);
    }
}
