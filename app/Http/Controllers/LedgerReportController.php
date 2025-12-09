<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\View\View;

class LedgerReportController extends Controller
{

    public function index(Request $request): View
    {
        $user = Session::get('user');
        $groupId = $user->group_id;
        $accounts = Account::where('group_id', $groupId)->get();

        $transactions = collect();
        $totalDebit = 0;
        $totalCredit = 0;

        if ($request->filled('account_id')) {
            $request->validate([
                'account_id' => 'required',
                'transaction_type' => 'nullable',
                'start_date' => 'nullable',
                'end_date' => 'nullable',
            ]);

            $accountIds = $request->input('account_id');
            if (!is_array($accountIds)) {
                $accountIds = explode(',', $accountIds);
            }

            $query = Transaction::with('account')
                ->where('group_id', $groupId)
                ->latest();

            $query->when(
                $accountIds && !in_array(Account::ALL, $accountIds),
                fn($q) => $q->whereIn('account_id', $accountIds)
            );

            $query->when(
                $request->filled('transaction_type'),
                function ($q) use ($request) {
                    $transactionType = $request->input('transaction_type');

                    if ($transactionType === Transaction::ALL) {
                        $q->whereIn('type', [Transaction::TYPE_DEBIT, Transaction::TYPE_CREDIT]);
                    } else {
                        $q->where('type', $transactionType);
                    }
                }
            );

            $query->when(
                $request->filled('start_date'),
                fn($q) => $q->whereDate(
                    'created_at',
                    '>=',
                    Carbon::parse($request->input('start_date'))->startOfDay()
                )
            );

            $query->when(
                $request->filled('end_date'),
                fn($q) => $q->whereDate(
                    'created_at',
                    '<=',
                    \Carbon\Carbon::parse($request->input('end_date'))->endOfDay()
                )
            );

            $transactions = $query->get();

            $runningBalance = 0;

            $transactions->transform(function ($transaction) use (&$runningBalance, &$totalDebit, &$totalCredit) {
                if (ucfirst($transaction->type) === Transaction::TYPE_DEBIT) {
                    $runningBalance += $transaction->amount;
                    $totalDebit += $transaction->amount;
                } elseif (ucfirst($transaction->type) === Transaction::TYPE_CREDIT) {
                    $runningBalance -= $transaction->amount;
                    $totalCredit += $transaction->amount;
                }

                $transaction->running_balance = $runningBalance;
                return $transaction;
            });
        }

        return view('accountLedgerReport.index', compact('transactions', 'accounts', 'user', 'totalDebit', 'totalCredit'));
    }
}
