<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Booking;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\Role;
use App\Models\User;
use App\Services\BookingService;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\ValidationException;

class BookingController extends Controller
{
    public function index()
    {
        $user = Session::get('user');
        $types = Product::types();
        $customers = Account::active()->where([
            ['type', Account::CUSTOMER],
            ['group_id', $user->group_id]
        ])->get();
        $recoveryMan = Account::active()->where([
            ['type', Account::EMPLOYEE],
            ['group_id', $user->group_id]
        ])->get();
        $purchasedProduct = Purchase::where('group_id', $user->group_id)
            ->where('quantity', '>', 0)
            ->whereHas('product')
            ->with('product')
            ->get();
        return view('booking.index', compact('user', 'customers', 'purchasedProduct', 'types', 'recoveryMan'));
    }

    public function store(Request $request)
    {
        try {
            $user = Session::get('user');
            $request->validate([
                'recovery_man_id'        => 'required',
                'account_id'        => 'required',
                'product_type'      => 'required|string',
                'product_id'        => 'required|exists:products,id',
                'purchase_id'       => 'required|exists:purchases,id',
                'imei_no'           => 'nullable|string',
                'deal_date'         => 'required|date',
                'total_payment'     => 'required|numeric|min:0',
                'discount_amount'   => 'required|numeric|min:0',
                'net_payment'        => 'required|numeric|min:0',
                'down_payment'      => 'required|numeric|min:0',
                'remaining_amount'  => 'required|numeric|min:0',
                'total_months'      => 'required|integer|min:1',
                'monthly_installment' => 'required',
                'start_month'       => 'required',
                'due_date'          => 'required|min:1|max:31',
            ]);
            $account = Account::find($request->account_id);
            $product = Product::find($request->product_id);
            $data = $request->all();
            $data['name']   = $account->name;
            $data['group_id']   = $user->group_id;
            $data['station_id'] = $user->station_id;

            $bookingService = new BookingService();
            $bookings = $bookingService->store($data);


            $notificationService = new NotificationService();
            $title = 'notification.booking_created_title';
            $message = 'notification.booking_created_message';

            $params = [
                'customer' => $account->name,
                'product'  => $product->product_name,
                'amount'   => $request->net_payment,
            ];
            $groupId = $user->group_id;
            $adminUserIds = User::where('group_id', $groupId)
                ->whereHas('roles', function ($q) {
                    $q->where('roles.name', Role::ROLE_ADMIN);
                })
                ->pluck('id')
                ->toArray();

            $module = Booking::BOOKING;
            $link = route('bookingManagement.index');
            $notificationService->store(
                $title,
                $message,
                $link,
                $adminUserIds,
                $module,
                $params,
                $groupId
            );
            return redirect()->route('bookingManagement.index')->with('success', 'Booking saved successfully.');
        } catch (ValidationException $e) {
            return redirect()->back()->with('error', $e->getMessage());
        } catch (\Exception $e) {
            Log::error('Error booking store : ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);
            return redirect()->route('bookingManagement.index')
                ->with('error', 'Error booking store: ' . $e->getMessage());
        }
    }

    public function getCustomerBookings($customerId)
    {
        $user = Session::get('user');
        $bookings = Booking::with('account', 'product')
            ->where('group_id', $user->group_id)
            ->where('account_id', $customerId)
            ->get();
        return response()->json($bookings);
    }

    public function getProduct($type)
    {
        $products = Purchase::with('product')->where('product_type', $type)->where('quantity', '>', 0)->get();
        return response()->json($products);
    }
}
