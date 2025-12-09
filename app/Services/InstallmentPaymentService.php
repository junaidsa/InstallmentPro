<?php

namespace App\Services;

use App\Models\Installment;
use Illuminate\Support\Facades\DB;

class InstallmentPaymentService
{
    /**
     * Handle installment payment
     *
     * @param Installment $installment
     * @param float $amount
     * @return array
     */
    public function pay(Installment $installment, float $amount): array
    {
        return DB::transaction(function () use ($installment, $amount) {
            $installmentAmount = $installment->amount;
            $paidAmount        = $installment->paid_amount ?? 0;

            $remaining = $installmentAmount - $paidAmount;
            if ($amount == $remaining) {
                $installment->update([
                    'paid_amount' => $paidAmount + $amount,
                    'status'      => 'paid',
                ]);

                return ['status' => 'success', 'message' => 'Installment fully paid'];
            }
            if ($amount < $remaining) {
                $installment->update([
                    'paid_amount' => $paidAmount + $amount,
                    'status'      => 'partial',
                ]);

                return ['status' => 'success', 'message' => 'Partial payment applied'];
            }
            if ($amount > $remaining) {
                $installment->update([
                    'paid_amount' => $installmentAmount,
                    'status'      => 'paid',
                ]);

                $extra = $amount - $remaining;

                $nextInstallment = Installment::where('booking_id', $installment->booking_id)
                    ->where('id', '>', $installment->id)
                    ->orderBy('id')
                    ->first();

                if ($nextInstallment) {
                    return $this->pay($nextInstallment, $extra);
                }

                return ['status' => 'success', 'message' => 'Installment fully paid. Extra ignored'];
            }

            return ['status' => 'error', 'message' => 'Invalid payment amount'];
        });
    }
}
