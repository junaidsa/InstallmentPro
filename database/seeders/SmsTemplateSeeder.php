<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SmsTemplate;

class SmsTemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {


        SmsTemplate::insert([
            [
                'group_id' => 1,
                'name' => 'Installment Due Reminder',
                'message' => 'Hi <customer_name>, your installment of PKR <installment_amount> for <month_name> is due. Last date to pay <due_date>. Thank you, <shop_name>.',
            ],
            [
                'group_id' => 1,
                'name' => 'Late Payment Reminder',
                'message' => 'Dear <customer_name>, your payment of PKR <installment_amount> was due on <due_date>. Please pay as soon as possible to avoid penalties. - <shop_name>',
            ],
            [
                'group_id' => 1,
                'name' => 'Payment Received',
                'message' => 'Hi <customer_name>, we have received your payment of PKR <installment_amount> for <month_name>. Thank you for your cooperation! - <shop_name>',
            ],
            [
                'group_id' => 1,
                'name' => 'Investor Amount Added',
                'message' => 'Dear <investor_name>, your investment of PKR <invested_amount> has been successfully added on <date>. Thank you for trusting <shop_name>.',
            ],
            [
                'group_id' => 1,
                'name' => 'Stock Alert',
                'message' => 'Alert: The stock for <product_name> is running low. Only <remaining_quantity> left in inventory. Please restock soon. - <shop_name>',
            ],
        ]);
    }
}
