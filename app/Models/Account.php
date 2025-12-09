<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Session;
use Laravel\Sanctum\HasApiTokens;

class Account extends Model
{

    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'group_id',
        'name',
        'type',
        'email',
        'contact',
        'father_name',
        'address',
        'cnic',
        'account_no',
        'balance',
        'investment_amount',
        'wage',
        'wage_type',
        'designation',
        'status',
        'total_investment',
        'image',
        'is_business',
    ];

    const CUSTOMER_MEDIA = 'customer_media';
    const CUSTOMER = 'Customer';
    const SUPPLIER = 'Supplier';
    const INVESTOR = 'Investor';
    const EMPLOYEE = 'Employee';
    const EXPENSE  = 'Expense';
    const SHOP  = 'Shop';
    const HOURLY = 'Hourly';
    const DAILY = 'Daily';
    const WEEKLY = 'Weekly';
    const MONTHLY = 'Monthly';
    const MANAGER = 'Manager';
    const ADMINISTRATOR = 'Administrator';
    const CLEANER = 'Cleaner';
    const MANAGING_DIRECTOR = 'Managing Director';
    const ACTIVE = 'active';
    const BLOCK = 'Block';
    const ALL = 'All';
    const BUSINESS_ACCOUNT = 'Business Account';

    public static function types(): array
    {
        return [
            self::CUSTOMER,
            self::SUPPLIER,
            self::EMPLOYEE,
            self::EXPENSE,
        ];
    }
    public const File_CNIC_FRONT = 'cnic_front';
    public const File_CNIC_BACK = 'cnic_back';
    public const File_DOCUMENT = 'document';
    public const File_IMAGE = 'image';

    public const DOCUMENT_TYPES = [
        self::File_CNIC_FRONT,
        self::File_CNIC_BACK,
        self::File_DOCUMENT,
        self::File_IMAGE,
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

    public function getCusAccountIdAttribute()
    {
        return 'CUS-' . str_pad($this->id, 6, '0', STR_PAD_LEFT);
    }
    public function transaction()
    {
        return $this->belongsToMany(Transaction::class, 'account_id');
    }
    public function guarantors()
    {
        return $this->hasMany(Guarantor::class, 'customer_id');
    }
    public function customerDocuments()
    {
        return $this->hasMany(CustomerDocument::class, 'customer_id');
    }
    public function scopeActive($query)
    {
        return $query->where('status', self::ACTIVE);
    }
}
