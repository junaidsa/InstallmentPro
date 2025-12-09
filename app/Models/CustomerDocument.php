<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Session;

class CustomerDocument extends Model
{
    use HasFactory;
    protected $fillable = [
        'group_id',
        'customer_id',
        'document_type',
        'document_path',
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
    public function customer()
    {
        return $this->belongsTo(Account::class, 'customer_id');
    }

    public function getDocumentUrlAttribute()
    {
        return asset($this->document_path);
    }
}
