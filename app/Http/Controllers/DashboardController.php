<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Booking;
use App\Models\Installment;
use App\Models\User;
use App\Services\NotificationService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;
use Illuminate\View\View;
use Illuminate\Http\Request;
use App\Models\Notification;
use App\Models\Purchase;
use App\Models\Transaction;
use Exception;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{

    const CHECKED_IN = 1;
    public function index(): View
    {

        $user = Session::get('user');
        $groupId = $user->group_id;
        $employeeId = $user->employee_id;
        $checkedIn = self::CHECKED_IN;
        $checkinStatus = User::where(['group_id' => $groupId, 'employee_id' => $employeeId])->first()->checkin_status;
        $totalCustomers = Account::where('type', Account::CUSTOMER)
            ->where('group_id', $user->group_id)
            ->count();

        $totalAvailablestock = Purchase::where('group_id', $user->group_id)->sum('quantity');

        $totasalestock = Booking::where('group_id', $user->group_id)->count();

        $total_payable = Booking::where('group_id', $user->group_id)->sum('total_payable');

        $today_sale = Booking::where('group_id', $user->group_id)
            ->whereDate('created_at', Carbon::today())
            ->sum('total_payable');

        $monthSale = Booking::where('group_id', $user->group_id)
            ->whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->sum('total_payable');

        $totalStock = $totalAvailablestock + $totasalestock;
        $receivedAmount = Installment::where('group_id', $user->group_id)
            ->whereIn('status', [Installment::STATUS_FULL_PAY, Installment::STATUS_PARTIAL_PAY])
            ->sum('paid_amount');

        $outstandingAmount =  $total_payable - $receivedAmount;
        $today = Carbon::today();
        $duePassed = Installment::with(['booking.account'])
            ->where('due_date', '<', $today)
            ->where('status', '!=', Installment::STATUS_FULL_PAY)
            ->get();


        $dueNear = Installment::with(['booking.account'])
            ->whereBetween('due_date', [$today, $today->copy()->addDays(20)])
            ->where('status', '!=', Installment::STATUS_FULL_PAY)
            ->get();
        $today = now()->format('Y-m-d');
        $start_date = Carbon::now()->startOfMonth()->format('Y-m-d');
        $end_date = Carbon::now()->endOfMonth()->format('Y-m-d');
        return view('dashboard.index', compact('checkinStatus', 'checkedIn', 'user', 'totalCustomers', 'totalStock', 'totasalestock', 'totalAvailablestock', 'receivedAmount', 'outstandingAmount', 'today_sale', 'monthSale', 'duePassed', 'dueNear', 'today', 'start_date', 'end_date'));
    }

    public function getNotificationList(Request $request)
    {
        $module = $request->get('module', Notification::ALL);
        $notificationService = new NotificationService();
        $notify = $notificationService->getData(limit: 5, paginate: true, onlyUnread: false, module: $module);
        return view('notification_list', compact('notify'))->render();
    }

    public function logJsError(Request $request)
    {
        $message = $request->input('message');
        $file = $request->input('file', 'unknown');
        $line = $request->input('line', 0);
        $payload = $request->all();

        Log::error('Frontend JS Error: ' . $message, $payload);
        try {
            throw new \ErrorException($message, 0, 1, $file, $line);
        } catch (Exception $e) {
            app(\App\Exceptions\Handler::class)->report($e);
        }

        return response()->json(['status' => 'ok']);
    }
}
