<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Booking;
use App\Models\Group;
use App\Models\Installment;
use App\Models\Notification;
use App\Models\PaymentLog;
use App\Models\Role;
use App\Models\Transaction;
use App\Models\User;
use App\Services\NotificationService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class InstallmentController extends Controller
{
    public function index()
    {
        $user = Session::get('user');
        $bookings = Booking::with('account', 'product')->where('group_id', $user->group_id)->get();
        $customers = Account::where([
            ['type', Account::CUSTOMER],
            ['group_id', $user->group_id]
        ])->get();
        $group = Group::find($user->group_id);
        return view('instalment.index', compact('bookings', 'user', 'customers', 'group'));
    }

    public function instalmentHistory($booking)
    {
        $user = Session::get('user');

        $installments = Installment::with('account')
            ->where('booking_id', $booking)
            ->where('station_id', $user->station_id)
            ->where('group_id', $user->group_id)
            ->get();
        return response()->json($installments);
    }
    public function remainingInstalment($booking)
    {
        $user = Session::get('user');

        $installments = Installment::with('account', 'booking')
            ->where('status', '!=', Installment::STATUS_FULL_PAY)
            ->where('booking_id', $booking)
            ->where('group_id', $user->group_id)
            ->get();

        return response()->json($installments);
    }


    public function storePayment(Request $request)
    {

        $request->validate([
            'installment_id' => 'required|exists:installments,id',
            'amount' => 'required|numeric|min:1',
            'payment_mode' => 'required|string',
            'remarks' => 'required|string',
            'late_payment_penalty' => 'required|numeric|min:0',
            'proof_of_payment' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        return DB::transaction(function () use ($request) {
            $installment = Installment::lockForUpdate()->findOrFail($request->installment_id);
            $lateFine = $request->late_payment_penalty;
            $totalDue = ($installment->amount - $installment->paid_amount) + $lateFine;
            $paid = $request->amount;
            $status = '';
            if ($paid < $totalDue) {
                $installment->paid_amount += $paid;
                $installment->remaining_amount = $totalDue - $installment->paid_amount;
                if ($installment->remaining_amount <= 0) {
                    $installment->remaining_amount = 0;
                    $status = Installment::STATUS_FULL_PAY;
                } else {
                    $status = Installment::STATUS_PARTIAL_PAY;
                }
            } else {
                $status = Installment::STATUS_FULL_PAY;
                $installment->paid_amount = $totalDue;
                $installment->remaining_amount = 0;
                $extra = $paid - $totalDue;

                if ($extra > 0) {
                    $nextInstallments = Installment::where('booking_id', $installment->booking_id)
                        ->where('id', '>', $installment->id)
                        ->whereIn('status', [Installment::STATUS_PENDING, Installment::STATUS_PARTIAL_PAY])
                        ->orderBy('id', 'asc')
                        ->get();

                    foreach ($nextInstallments as $next) {
                        if ($extra <= 0)
                            break;

                        $remainingNext = $next->amount - $next->paid_amount;

                        if ($extra >= $remainingNext) {
                            $next->paid_amount = $next->amount;
                            $next->remaining_amount = 0;
                            $next->status = Installment::STATUS_FULL_PAY;
                            $extra -= $remainingNext;
                        } else {
                            $next->paid_amount += $extra;
                            $next->remaining_amount = $next->amount - $next->paid_amount;
                            $next->status = Installment::STATUS_PARTIAL_PAY;
                            $extra = 0;
                        }
                        $next->save();
                    }
                }
            }

            $installment->late_payment_penalty = $lateFine;
            $installment->status = $status;
            $installment->remarks = $request->remarks;
            $installment->updated_at = now();
            if ($request->hasFile('proof_of_payment')) {
                $proofFile = $request->file('proof_of_payment');
                $proofFileName = time() . '_proof_' . $installment->id . '.' . $proofFile->getClientOriginalExtension();
                $destination = public_path(Installment::PROOF_UPLOAD_PATH);
                $proofFile->move($destination, $proofFileName);
                $installment->image = Installment::PROOF_UPLOAD_PATH . '/' . $proofFileName;
            }

            $installment->save();
            $transaction = Transaction::create([
                'group_id' => $installment->group_id,
                'station_id' => $installment->station_id,
                'account_id' => $installment->account_id,
                'booking_id' => $installment->booking_id,
                'installment_id' => $installment->id,
                'amount' => $paid,
                'payment_mode' => $request->payment_mode,
                'type' => Transaction::TYPE_CREDIT,
                'remaining' => $installment->remaining_amount,
                'remarks' => $status,
                'created_by' => auth()->id(),
            ]);
            PaymentLog::create([
                'group_id' => $installment->group_id,
                'account_id' => $installment->account_id,
                'station_id' => $installment->station_id,
                'installment_id' => $installment->id,
                'transaction_id' => $transaction->id,
                'amount' => $paid,
                'payment_mode' => $request->payment_mode,
                'created_by' => auth()->id(),
            ]);

            $shopAccount = Account::where('name', Account::SHOP)
                ->where('type', Account::EXPENSE)
                ->first();

            if ($shopAccount) {
                $shopAccount->increment('balance', $paid);
            } else {
                Log::warning('Shop account not found for increment.');
            }

            $notificationService = new NotificationService();
            $notificationService->store(
                'notification.installment_paid_title',
                'notification.installment_paid_message',
                route('payinstalment.index'),
                User::where('group_id', $installment->group_id)
                    ->whereHas('roles', fn($q) => $q->where('roles.name', Role::ROLE_ADMIN))
                    ->pluck('id')
                    ->toArray(),
                Notification::INSTALLMENT,
                [
                    'customer' => $installment->account->name,
                    'product' => $installment->booking->product->product_name,
                    'amount' => number_format($paid, 2),
                ],
                $installment->group_id
            );

            return response()->json([
                'message' => 'Payment recorded successfully',
                'installment' => $installment,
                'transaction' => $transaction,
            ]);
        });
    }



    public function getcalendarData(Request $request)
    {
        if ($request->has(['start', 'end'])) {
            $installments = Installment::with('account')
                ->whereBetween('due_date', [$request->start, $request->end])
                ->get()
                ->map(function ($inst) {
                    $today = now()->toDateString();
                    $isLate = $inst->status !== Installment::STATUS_FULL_PAY && $inst->due_date < $today;
                    $isNearDue = !$isLate &&
                        $inst->status !== Installment::STATUS_FULL_PAY &&
                        Carbon::parse($inst->due_date)->between(
                            now(),
                            now()->addDays(7)
                        );

                    return [
                        'id' => $inst->id,
                        'customer_id' => $inst->account->id,
                        'title' => $inst->account->name . ' - ' . number_format($inst->amount),
                        'start' => $inst->due_date,
                        'status' => ucfirst($inst->status),
                        'is_late' => $isLate,
                        'is_near_due' => $isNearDue,
                        'allDay' => true,
                    ];
                });

            return response()->json($installments);
        }

        if ($request->has('date')) {
            $installments = Installment::with('account')
                ->whereDate('due_date', $request->date)
                ->get()
                ->map(function ($inst) {
                    $today = now()->toDateString();
                    $isLate = $inst->status !== Installment::STATUS_FULL_PAY && $inst->due_date < $today;
                    $isNearDue = !$isLate &&
                        $inst->status !== Installment::STATUS_FULL_PAY &&
                        Carbon::parse($inst->due_date)->between(
                            now(),
                            now()->addDays(7)
                        );

                    return [
                        'id' => $inst->id,
                        'customer_id' => $inst->account->id,
                        'customer_name' => $inst->account->name,
                        'amount' => number_format($inst->amount),
                        'due_date_formatted' => $inst->due_date,
                        'status' => ucfirst($inst->status),
                        'is_late' => $isLate,
                        'is_near_due' => $isNearDue,
                    ];
                });

            return response()->json($installments);
        }

        return response()->json([]);
    }

    public function showReceipt($id)
    {
        $user = Session::get('user');
        $group = Group::find($user->group_id);
        $transaction = Transaction::with([
            'installment.account',
            'installment.booking.product',
            'installment.booking.installments',
            'paymentLog',
        ])->findOrFail($id);

        $installment = $transaction->installment;
        $received = $installment->booking->installments->where('status', '!=', Installment::STATUS_PENDING)->sum('paid_amount');
        $remaining = $installment->booking->installments->where('status', '!=', Installment::STATUS_FULL_PAY)->sum('remaining_amount');
        return view('instalment.receipt', compact('transaction', 'installment', 'user', 'group', 'received', 'remaining'));
    }

    public function getRecoverySheet(Request $request)
    {
        $user = Session::get('user');

        $employees = Account::where([
            ['type', Account::EMPLOYEE],
            ['group_id', $user->group_id]
        ])->get();

        $installments = collect();

        if ($request->has('recovery_man_id') && $request->recovery_man_id) {
            $query = Installment::with(['account', 'booking'])
                ->where('group_id', $user->group_id)
                ->whereHas('booking', function ($q) use ($request) {
                    $q->where('recovery_man_id', $request->recovery_man_id);
                });
            if ($request->date) {
                $query->where('due_date', '<=', $request->date);
            }

            if ($request->status && $request->status !== Installment::ALL) {
                if ($request->status === Installment::STATUS_PENDING) {
                    $query->wherein('status', [Installment::STATUS_PENDING, Installment::STATUS_PARTIAL_PAY]);
                } elseif ($request->status === Installment::STATUS_PARTIAL_PAY) {
                    $query->where('status', Installment::STATUS_PARTIAL_PAY);
                } elseif ($request->status === Installment::STATUS_FULL_PAY) {
                    $query->where('status', Installment::STATUS_FULL_PAY);
                }
            }

            $installments = $query->orderBy('due_date', 'asc')->get();
        }

        $statuses = Installment::STATUSES;

        return view('recoverySheet.index', compact('user', 'employees', 'installments', 'statuses'));
    }
}
