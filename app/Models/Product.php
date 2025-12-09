<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Session;

class Product extends Model
{
    use HasFactory;
    protected $fillable = [
        'group_id',
        'product_type',
        'product_name',
        'product_company',
        'product_details',
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
    public function purchases()
    {
        return $this->hasMany(Purchase::class, 'product_id');
    }
    const PRODUCT_TYPES = [
        'Mobiles',
        'Agricultural',
        'Electronics',
        'Household',
        'Appliances',
        'Furniture',
        'Automotive',
        'Solar',
        'Other',
    ];

    public static function types(): array
    {
        return self::PRODUCT_TYPES;
    }
}
