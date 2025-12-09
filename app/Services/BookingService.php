<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\Purchase;
use App\Models\Installment;
use App\Models\Transaction;
use App\Models\PaymentLog;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class BookingService
{
    public function store(array $data)
    {
        return DB::transaction(function () use ($data) {
            $booking = Booking::create([
                'group_id'          => $data['group_id'],
                'station_id'        => $data['station_id'],
                'account_id'        => $data['account_id'],
                'recovery_man_id'   => $data['recovery_man_id'],
                'name'              => $data['name'],
                'property_type'     => $data['product_type'],
                'product_id'        => $data['product_id'],
                'purchase_id'       => $data['purchase_id'],
                'imei_no'           => $data['imei_no'] ?? null,
                'deal_date'         => Carbon::parse($data['deal_date'])->format('Y-m-d'),
                'total_amount'      => $data['total_payment'],
                'discount_amount'   => $data['discount_amount'],
                'total_payable'     => $data['net_payment'],
                'down_payment'      => $data['down_payment'],
                'remaining_amount'  => $data['remaining_amount'],
                'total_months'      => $data['total_months'],
                'monthly_installment' => $data['monthly_installment'],
                'start_month'       => $data['start_month'],
                'due_date'          => $data['due_date'],
                'late_payment_penalty' => $data['late_payment_penalty'],
                'warranty_period' => $data['warranty_period'] ?? null,
                'warranty_expiry' => !empty($data['warranty_period'])
                    ? Carbon::parse($data['deal_date'])->addMonths($data['warranty_period'])
                    : null,
            ]);

            Purchase::where('id', $data['purchase_id'])->decrement('quantity', 1);
            $downPaymentInstallment = $this->createDownPayment($booking, $data);

            $this->createDownPaymentTransaction($booking, $downPaymentInstallment, $data);
            $this->generateInstallments($booking, $data);
            return $booking;
        });
    }

    private function createDownPayment($booking, array $data)
    {
        $startDate = Carbon::parse($data['start_month']);
        $dueDate   = $startDate->copy()->day($data['due_date'])->endOfDay();
        return Installment::create([
            'group_id'          => $data['group_id'],
            'account_id'        => $data['account_id'],
            'booking_id'        => $booking->id,
            'installment_title' => Transaction::DOWN_PAYMENT,
            'month'             => $dueDate->month,
            'year'              => $dueDate->year,
            'amount'            => $data['down_payment'],
            'paid_amount'       => $data['down_payment'],
            'remaining_amount'  =>  $data['remaining_amount'],
            'due_date'          => $dueDate,
            'status'            => Installment::STATUS_FULL_PAY,
            'remarks'            => Transaction::DOWN_PAYMENT,
            'created_by'            => Auth::id(),
            'updated_by'            => Auth::id(),
            'updated_at'            => now(),
            'created_at'            => now(),
        ]);
    }


    private function createDownPaymentTransaction($booking, $installment, array $data)
    {
        $transaction = Transaction::create([
            'group_id'       => $data['group_id'],
            'station_id'     => $data['station_id'],
            'account_id'     => $data['account_id'],
            'booking_id'     => $booking->id,
            'installment_id' => $installment->id,
            'amount'         => $data['down_payment'],
            'type'           => Transaction::TYPE_CREDIT,
            'balance'        => 0,
            'remarks'        => Transaction::DOWN_PAYMENT,
        ]);

        PaymentLog::create([
            'group_id'       => $data['group_id'],
            'station_id'     => $data['station_id'],
            'account_id'     => $data['account_id'],
            'installment_id' => $installment->id,
            'transaction_id' => $transaction->id,
            'amount'         => $data['down_payment'],
            'payment_mode'   => PaymentLog::CASH,
        ]);
    }
    private function generateInstallments($booking, array $data)
    {
        $startDate  = Carbon::parse($data['start_month']);
        $month      = (int) $startDate->month;
        $year       = (int) $startDate->year;
        $counter    = 1;
        $titleNo    = 1;
        $shortMonths = [4, 6, 9, 11];

        while ($counter <= $data['total_months']) {
            if ($month > 12) {
                $month = 1;
                $year++;
            }

            $dueDate = $this->calculateDueDate($year, $month, $data['due_date'], $shortMonths);

            Installment::create([
                'group_id'          => $data['group_id'],
                'recovery_man_id'   => $data['recovery_man_id'],
                'station_id'        => $data['station_id'],
                'account_id'        => $data['account_id'],
                'booking_id'        => $booking->id,
                'installment_title' => "Installment {$titleNo}",
                'month'             => $month,
                'year'              => $year,
                'amount'            => $data['monthly_installment'],
                'paid_amount'       => 0,
                'remaining_amount'  => $data['monthly_installment'],
                'due_date'          => $dueDate,
                'status'            => Installment::STATUS_PENDING,
                'created_at'        => now(),
            ]);

            $month++;
            $counter++;
            $titleNo++;
        }
    }


    private function calculateDueDate($year, $month, $day, array $shortMonths)
    {
        if (in_array($month, $shortMonths) && $day == 31) {
            return Carbon::create($year, $month, 30, 23, 59, 59);
        }

        if ($month == 2 && $day > 28) {
            return Carbon::create($year, $month, 28, 23, 59, 59);
        }

        return Carbon::create($year, $month, $day, 23, 59, 59);
    }
}
