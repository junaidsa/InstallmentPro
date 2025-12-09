<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Installment;
use App\Services\SmsService;

class SendInstallmentReminders extends Command
{
    protected $signature = 'reminder:installment';
    protected $description = 'Send SMS reminders for upcoming installments';

    public function handle()
    {
        $installments = Installment::with('account')
            ->whereDate('due_date', '>=', now())
            ->whereDate('due_date', '<=', now()->addDays(7))
            ->where('status', '!=', Installment::STATUS_FULL_PAY)
            ->get();

        foreach ($installments as $installment) {
            $account = $installment->account;
            if (!$account || !$account->contact) continue;

            $message = "Dear {$account->name}, your installment of Rs.{$installment->amount} "
                . "is due on {$installment->due_date->format('d M, Y')}. "
                . "Please pay on time to avoid any penalty.";

            SmsService::send($account->contact, $message, [
                'name' => $account->name,
                'amount' => $installment->amount,
                'due_date' => $installment->due_date->format('Y-m-d'),
            ]);
        }

        $this->info('Installment reminders sent successfully.');
    }
}
