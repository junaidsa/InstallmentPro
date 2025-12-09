<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SmsTrigger extends Model
{
    use HasFactory;
    protected $fillable = ['group_id', 'trigger_name', 'sms_template_id'];
    const INSTALLMENT_DUE = 'installment_due';
    const LATE_INSTALLMENT = 'late_installment';
    const PAYMENT_RECEIVED = 'payment_received';
    const INVESTOR_AMOUNT_ADDED = 'investor_amount_added';
    const INVESTOR_STOCK_ALERT = 'Stock Alert';

    public static function allTriggers()
    {
        return [
            self::INSTALLMENT_DUE => 'Installment Due Reminder',
            self::LATE_INSTALLMENT => 'Late Payment Reminder',
            self::PAYMENT_RECEIVED => 'Payment Received',
            self::INVESTOR_AMOUNT_ADDED => 'Investor Amount Added',
            self::INVESTOR_STOCK_ALERT => 'Stock Alert',
        ];
    }
    public function template()
    {
        return $this->belongsTo(SmsTemplate::class, 'sms_template_id');
    }
}
