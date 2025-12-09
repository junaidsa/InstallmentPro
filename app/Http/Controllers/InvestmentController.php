<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Investment;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Services\ProfitDistributionService;
use Illuminate\Support\Facades\DB;

class InvestmentController extends Controller
{
    public function index()
    {
        $user = Session::get('user');
        $groupId = $user->group_id;
        $invesments = Investment::with('account')->where('group_id', $groupId)->get();
        $totalInvestmentAmount = $invesments->sum('amount');
        $investors = Account::where([
            ['type', Account::INVESTOR],
            ['group_id', $user->group_id]
        ])->get();
        return view('investment.index', compact('user', 'invesments', 'investors', 'totalInvestmentAmount'));
    }

    public function store(Request $request)
    {
        $groupId = Session::get('user')->group_id;
        $userId  = Session::get('user')->id;

        $request->validate([
            'account_id'      => 'required|exists:accounts,id',
            'amount'          => 'required|numeric|min:1',
            'investment_date' => 'required|date',
        ]);
        DB::transaction(function () use ($request, $groupId, $userId) {
            $account = Account::findOrFail($request->account_id);
            $account->increment('investment_amount', $request->amount);
            $investment = Investment::create([
                'group_id'        => $groupId,
                'account_id'      => $account->id,
                'investor_name'   => $account->name,
                'amount'          => $request->amount,
                'total_amount'    => $account->investment_amount,
                'investment_date' => $request->investment_date,
            ]);
            $shopAccount = Account::where('name', Account::SHOP)->where('group_id', $groupId)->first();
            $shopAccount->increment('balance', $request->amount);
            Transaction::create([
                'group_id'   => $groupId,
                'account_id' => $account->id,
                'type'       => Transaction::TYPE_CREDIT,
                'amount'     => $request->amount,
                'remarks'    => Transaction::INVESTMENT_REMARKS,
                'narration'  => 'Investment for ' . now()->format('F Y'),
                'created_by' => $userId,
            ]);
        });

        return redirect()->back()->with('success', 'Investment added successfully');
    }




    public function transferProfit(Request $request)
    {
        $validation = $request->validate([
            'investor_id' => 'required',
            'reinvested_amount' => 'required',
            'cash_out_amount' => 'required',
            'shop_profit' => 'required',
        ]);
        $user = Session::get('user');
        $groupId = $user->group_id;

        $investorId = $request->input('investor_id');
        $reinvestedAmount = floatval($request->input('reinvested_amount'));
        $cashOutAmount = round((float) $request->input('cash_out_amount'), 2);
        $shopProfit = round((float) $request->input('shop_profit'), 2);
        $totalAvailableProfit = round((float) $request->input('total_available_profit'), 2);
        try {
            $profitDistribution = new ProfitDistributionService();
            $message = $profitDistribution->distributeProfit(
                $groupId,
                $user->id,
                $investorId,
                $reinvestedAmount,
                $cashOutAmount,
                $totalAvailableProfit,
                $shopProfit
            );
            $shopMessage = $profitDistribution->distributeShopProfit($groupId, $user->id, $shopProfit);
            return redirect()->back()->with('success', $message);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
