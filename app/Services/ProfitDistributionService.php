<?php

namespace App\Services;

use App\Models\Account;
use App\Models\Booking;
use App\Models\CashOut;
use App\Models\Investment;
use App\Models\Purchase;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;

class ProfitDistributionService
{
    public function distributeProfit(
        $groupId,
        $userId,
        $investorId,
        $reinvestedAmount,
        $cashOutAmount,
        $totalAvailableProfit,
        $shopProfit = 0
    ) {
        $currentMonth = now()->month;
        $currentYear = now()->year;
        $currentDate = now()->startOfMonth();

        DB::beginTransaction();

        try {
            $investor = Account::where('group_id', $groupId)
                ->where('type', Account::INVESTOR)
                ->findOrFail($investorId);
            if ($totalAvailableProfit <= 0) {
                throw new \Exception('No profit available to distribute this month.');
            }
            $alreadyProfitAction = CashOut::where('group_id', $groupId)
                ->where('account_id', $investorId)
                ->whereYear('cashout_date', $currentYear)
                ->whereMonth('cashout_date', $currentMonth)
                ->exists()
                ||
                Investment::where('group_id', $groupId)
                ->where('account_id', $investorId)
                ->whereYear('investment_date', $currentYear)
                ->whereMonth('investment_date', $currentMonth)
                ->where('status', Investment::REINVESTED)
                ->exists();

            if ($alreadyProfitAction) {
                throw new \Exception("Profit for {$investor->name} already distributed this month.");
            }
            if ($reinvestedAmount > 0) {
                Investment::create([
                    'group_id'        => $groupId,
                    'account_id'      => $investor->id,
                    'investor_name'   => $investor->name,
                    'amount'          => $reinvestedAmount,
                    'total_amount'    => $investor->investment_amount + $reinvestedAmount,
                    'investment_date' => now(),
                    'status'          => Investment::REINVESTED,
                    'created_by'      => $userId,
                ]);

                Transaction::create([
                    'group_id'   => $groupId,
                    'account_id' => $investor->id,
                    'type'       => Transaction::TYPE_CREDIT,
                    'amount'     => $reinvestedAmount,
                    'remarks'    => Transaction::INVESTMENT_REMARKS,
                    'narration'  => 'Reinvested profit for ' . now()->format('F Y'),
                    'created_by' => $userId,
                ]);
                $shopAccount = Account::where('group_id', $groupId)
                    ->where('name', Account::SHOP)
                    ->firstOrFail();
                $shopAccount->increment('balance', $reinvestedAmount);
                $investor->increment('investment_amount', $reinvestedAmount);
            }
            if ($cashOutAmount > 0) {
                CashOut::create([
                    'group_id'     => $groupId,
                    'account_id'   => $investor->id,
                    'amount'       => $cashOutAmount,
                    'type'         => CashOut::TYPE_CASH_OUT,
                    'cashout_date' => $currentDate,
                    'narration'    => 'Cash out profit for ' . now()->format('F Y'),
                    'created_by'   => $userId,
                ]);
            }

            DB::commit();
            return "Profit distributed for {$investor->name}: Cash Out = {$cashOutAmount}, Reinvested = {$reinvestedAmount}";
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }


    public function distributeShopProfit($groupId, $userId, $shopProfit)
    {
        $currentMonth = now()->month;
        $currentYear = now()->year;
        $currentDate = now()->startOfMonth();

        DB::beginTransaction();

        try {

            if ($shopProfit <= 0) {
                throw new \Exception('No shop profit available to distribute this month.');
            }
            $shopAccount = Account::where('group_id', $groupId)
                ->where('name', Account::SHOP)
                ->firstOrFail();
            $alreadyExists = CashOut::where('group_id', $groupId)
                ->where('account_id', $shopAccount->id)
                ->whereYear('cashout_date', $currentYear)
                ->whereMonth('cashout_date', $currentMonth)
                ->exists();

            if ($alreadyExists) {
                DB::rollBack();
                return "Shop profit for this month has already been distributed.";
            }
            CashOut::create([
                'group_id'     => $groupId,
                'account_id'   => $shopAccount->id,
                'amount'       => $shopProfit,
                'type'         => CashOut::TYPE_SHOP_PROFIT,
                'cashout_date' => $currentDate,
                'narration'    => 'Shop profit for ' . now()->format('F Y'),
                'created_by'   => $userId,
            ]);
            Transaction::create([
                'group_id'   => $groupId,
                'account_id' => $shopAccount->id,
                'type'       => Transaction::TYPE_CREDIT,
                'amount'     => $shopProfit,
                'remarks'    => Transaction::MONTHLY_SHOP_PROFIT_REMARK,
                'narration'  => 'Shop profit for ' . now()->format('F Y'),
                'created_by' => $userId,
            ]);
            $shopAccount->increment('balance', $shopProfit);

            DB::commit();

            return "Shop profit of " . round($shopProfit) . " successfully distributed.";
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
