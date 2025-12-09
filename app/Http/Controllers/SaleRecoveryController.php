<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\PaymentLog;
use App\Models\SaleRecovery;
use App\Models\RecoveryPayment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SaleRecoveryController extends Controller
{
    public function index(Request $request)
    {
        $all = Account::ALL;
        $pending = PaymentLog::PENDING;
        $recovery_man_id = $request->recovery_man_id;
        $user = session('user');

        $recoveryMans = User::where('group_id', $user->group_id)->whereNotNull('employee_id')
            ->get();
        $employees = Account::active()
            ->where('type', Account::EMPLOYEE)
            ->where('group_id', $user->group_id)
            ->get();
        $pandingRecovery = collect();
        $recoveries = collect();
        if ($request->filled('recovery_man_id')) {
            $recoveryManIds = $recovery_man_id === $all
                ? $employees->pluck('id')->toArray()
                : [$recovery_man_id];
            $recoveryManUserIds = User::whereIn('employee_id', $recoveryManIds)->pluck('id');
            $recoveries = PaymentLog::with(['account', 'installment', 'recoveryman'])
                ->where('group_id', $user->group_id)
                ->where('is_approve', PaymentLog::PENDING)
                ->when($request->recovery_date, function ($query) use ($request) {
                    $query->whereDate('created_at', $request->recovery_date);
                })
                ->whereIn('created_by', $recoveryManUserIds)
                ->get();
            $pandingRecovery = SaleRecovery::with(['recoveryMan'])
                ->where('group_id', $user->group_id)
                ->where('status', SaleRecovery::STATUS_PENDING)
                ->when($request->recovery_date, function ($query) use ($request) {
                    $query->whereDate('created_at', $request->recovery_date);
                })
                ->whereIn('recovery_man_id', $recoveryManIds)
                ->get();
        }

        return view('sale_recovery.index', compact('user', 'employees', 'recoveries', 'all', 'recoveryMans', 'pandingRecovery', 'recovery_man_id', 'pending'));
    }

    public function store(Request $request)
    {
        $user = session('user');
        $recovery_man_id = $request->recovery_man_id;

        $total_amount = (float)$request->total_amount;
        $given_amount = (float)$request->given_amount;
        if ($given_amount > $total_amount) {
            return response()->json([
                'success' => false,
                'message' => 'Given amount cannot exceed total amount.',
            ], 422);
        }

        $remaining_amount = $total_amount - $given_amount;

        $saleRecovery = SaleRecovery::create([
            'group_id' => $user->group_id,
            'recovery_man_id' => $recovery_man_id,
            'total_amount' => $total_amount,
            'amount' => $given_amount,
            'remaining_amount' => $remaining_amount,
            'payment_method' => $request->payment_method,
            'remarks' => $request->remarks,
            'status' => $remaining_amount == 0 ? SaleRecovery::STATUS_COMPLETED : SaleRecovery::STATUS_PENDING,
            'approved_at' => now(),
            'approved_by' => Auth::id(),
        ]);

        RecoveryPayment::create([
            'group_id' => $user->group_id,
            'sale_recovery_id' => $saleRecovery->id,
            'recovery_man_id' => $recovery_man_id,
            'amount' => $given_amount,
        ]);

        $grouped_installments = json_decode($request->grouped_installments, true);
        $installmentIds = [];

        foreach ($grouped_installments as $manId => $data) {
            foreach ($data['installments'] as $inst) {
                $installmentIds[] = $inst['id'];
            }
        }
        PaymentLog::whereIn('id', $installmentIds)
            ->update([
                'is_approve' => PaymentLog::APPROVE,
                'sale_recovery_id' => $saleRecovery->id,
            ]);

        return response()->json([
            'success' => true,
            'message' => 'Sale recovery saved successfully.',
        ]);
    }

    public function approve(Request $request)
    {
        $saleRecovery = SaleRecovery::find($request->id);
        if (!$saleRecovery || $saleRecovery->status != SaleRecovery::STATUS_PENDING) {
            return response()->json(['success' => false, 'message' => 'Invalid recovery']);
        }

        $remaining = $saleRecovery->remaining_amount;
        $saleRecovery->update([
            'remaining_amount' => 0,
            'status' => SaleRecovery::STATUS_COMPLETED,
            'approved_at' => now(),
            'approved_by' => Auth::id(),
        ]);

        RecoveryPayment::create([
            'group_id' => $saleRecovery->group_id,
            'sale_recovery_id' => $saleRecovery->id,
            'recovery_man_id' => $saleRecovery->recovery_man_id,
            'amount' => $remaining,
        ]);

        PaymentLog::where('sale_recovery_id', $saleRecovery->id)->update([
            'is_approve' => PaymentLog::APPROVE,
        ]);

        return response()->json(['success' => true, 'message' => 'Approved successfully']);
    }

    public function approveAll(Request $request)
    {
        $user = session('user');
        $recovery_date = $request->recovery_date;
        $pendingRecoveries = PaymentLog::where('group_id', $user->group_id)
            ->where('is_approve', PaymentLog::PENDING)
            ->when($recovery_date, fn($q) => $q->whereDate('created_at', $recovery_date))
            ->get();

        if ($pendingRecoveries->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'No pending recoveries found to approve.'
            ]);
        }
        $recoveryManTotals = $pendingRecoveries->groupBy('created_by')->map(fn($group) => $group->sum('amount'));

        foreach ($recoveryManTotals as $recoveryManUserId => $totalAmount) {

            $recoveryManEmployee = User::find($recoveryManUserId);
            if (!$recoveryManEmployee) continue;

            $employeeAccount = Account::where('id', $recoveryManEmployee->employee_id)->first();
            if (!$employeeAccount) continue;
            $saleRecovery = SaleRecovery::create([
                'group_id' => $user->group_id,
                'recovery_man_id' => $employeeAccount->id,
                'total_amount' => $totalAmount,
                'amount' => $totalAmount,
                'remaining_amount' => 0,
                'payment_method' => SaleRecovery::PAYMENT_METHOD_CASH,
                'remarks' => 'Bulk approved - ' . ($recovery_date ? "Date: $recovery_date" : 'All pending recoveries'),
                'status' => SaleRecovery::STATUS_COMPLETED,
                'approved_at' => now(),
                'approved_by' => Auth::id(),
            ]);
            RecoveryPayment::create([
                'group_id' => $saleRecovery->group_id,
                'sale_recovery_id' => $saleRecovery->id,
                'recovery_man_id' => $saleRecovery->recovery_man_id,
                'amount' => $totalAmount,
            ]);
            $pendingLogsForMan = $pendingRecoveries
                ->where('created_by', $recoveryManUserId)
                ->pluck('id')
                ->toArray();

            PaymentLog::whereIn('id', $pendingLogsForMan)->update([
                'is_approve' => PaymentLog::APPROVE,
                'sale_recovery_id' => $saleRecovery->id,
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'All recoveries approved successfully.'
        ]);
    }
}
