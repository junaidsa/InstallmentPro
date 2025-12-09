<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Booking;
use App\Models\Group;
use App\Models\Installment;
use App\Models\Investment;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\SaleRecovery;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class ReportController extends Controller
{


    public function statusReport(Request $request)
    {
        $user = Session::get('user');
        $groupId = $user->group_id;
        $Full_PAY = Installment::STATUS_FULL_PAY;
        $PENDING = Installment::STATUS_PENDING;
        $records = collect();
        $totalAmount = $receivedAmount = $outstandingAmount = 0;

        if ($request->has('searchPerson')) {
            $selectedMonth = $request->input('selectedMonth');
            [$year, $month] = explode('-', $selectedMonth);
            $records = Installment::with([
                'account:id,name,contact',
                'booking.product:id,product_name,product_company',
                'paymentLogs:id,installment_id,transaction_id,amount,payment_mode,created_at'
            ])
                ->select([
                    'id',
                    'account_id',
                    'booking_id',
                    'installment_title',
                    'month',
                    'year',
                    'amount',
                    'paid_amount',
                    'remaining_amount',
                    'due_date',
                    'status',
                    'group_id',
                    'image'
                ])
                ->where('year', $year)
                ->where('month', $month)
                ->where('amount', '!=', 0)
                ->where('group_id', $groupId)
                ->orderBy('due_date', 'ASC')
                ->get();
            $records->each(function ($record) {
                $record->latestPaymentLog = $record->paymentLogs->sortByDesc('created_at')->first();
            });
            $receivedAmount = Installment::where('group_id', $groupId)
                ->where('year', $year)
                ->where('month', $month)
                ->sum('paid_amount');

            $totalAmount = Installment::where('group_id', $groupId)
                ->where('year', $year)
                ->where('month', $month)
                ->sum('amount');
            $outstandingAmount = $totalAmount - $receivedAmount;
        }

        return view('reports.statusReport', compact(
            'records',
            'totalAmount',
            'receivedAmount',
            'outstandingAmount',
            'user',
            'Full_PAY',
            'PENDING'
        ));
    }

    public function stockReport(Request $request)
    {
        $user = Session::get('user');
        $groupId = $user->group_id;
        $productId = $request->input('productDD', 'all_products');
        $productType = $request->input('product_type', null);
        $stockType = $request->input('stock_type', 'All');
        $products = Product::where('group_id', $groupId)->get();
        $types = Product::types();
        $stockTypeList = Purchase::types();
        $query = Purchase::with(['product:id,product_name,product_company', 'account:id,name,type'])
            ->where('group_id', $groupId)
            ->whereHas('account', function ($q) {
                $q->where('type', Account::SUPPLIER);
            });

        if ($stockType !== Purchase::STOCK_TYPE[0]) {
            if ($stockType === Purchase::STOCK_TYPE[1]) {
                $query->where('quantity', '=', 0);
            } elseif ($stockType === Purchase::STOCK_TYPE[2]) {
                $query->where('quantity', '>', 0);
            }
        }

        if (!empty($productType)) {
            $query->where('product_type', $productType);
        }
        if ($productId !== 'all_products') {
            $query->where('product_id', $productId);
        }

        $records = $query->orderBy('created_at', 'DESC')->get();

        return view('reports.stockReport', compact(
            'records',
            'products',
            'productId',
            'productType',
            'stockType',
            'user',
            'types',
            'stockTypeList'
        ));
    }



    public function phoneDetailsReport(Request $request)
    {
        $user = Session::get('user');
        $groupId = $user->group_id;
        $imei = $request->input('imei');
        $records = collect();

        if ($request->has('imei')) {
            $records = Booking::with('product', 'account')
                ->when($request->filled('imei'), function ($query) use ($request) {
                    $query->where('imei_no', 'like', '%' . $request->imei . '%');
                })
                ->orderBy('created_at', 'DESC')
                ->get();
        }

        return view('reports.phoneDetails', compact('records', 'user', 'imei'));
    }
    public function contractDetails(Request $request)
    {
        $user = Session::get('user');

        $customers = Account::where([
            ['type', Account::CUSTOMER],
            ['group_id', $user->group_id]
        ])->get();

        $bookings = collect();

        if ($request->filled('account_id')) {
            $query = Booking::with([
                'account.guarantors',
                'account.customerDocuments',
                'product',
                'installments.updatedBy',
                'installments.paymentLogs'
            ])
                ->where('account_id', $request->account_id)
                ->where('group_id', $user->group_id);

            if ($request->filled('booking_id')) {
                $query->where('id', $request->booking_id);
            }

            $bookings = $query->get();

            $bookings->transform(function ($booking) {
                $totalPayable = $booking->total_payable ?? 0;
                $totalPaid = $booking->installments->sum('paid_amount') ?? 0;
                $outstanding = $totalPayable - $totalPaid;
                $penalty = $booking->installments->sum('late_payment_penalty') ?? 0;
                $receivedPercentage = $totalPayable > 0 ? round(($totalPaid / $totalPayable) * 100, 2) : 0;

                $booking->payment_details = (object) [
                    'net_amount' => $totalPayable,
                    'received_amount' => $totalPaid,
                    'outstanding_amount' => $outstanding,
                    'penalty_amount' => $penalty,
                    'received_percentage' => $receivedPercentage,
                ];

                return $booking;
            });
        }

        $booking_id = $request->booking_id;

        return view('contractDetails.index', compact('customers', 'bookings', 'user', 'booking_id'));
    }


    public function installmentReport()
    {
        $user = Session::get('user');
        $bookings = Booking::with('account', 'product')->where('group_id', $user->group_id)->get();
        $customers = Account::where([
            ['type', Account::CUSTOMER],
            ['group_id', $user->group_id]
        ])->get();
        $group = Group::find($user->group_id);
        return view('reports.installmentReport', compact('bookings', 'customers', 'user', 'group'));
    }
    public function investorIncomeReport(Request $request)
    {
        $user     = Session::get('user');
        $groupId  = $user->group_id;

        $investors = Account::where('type', Account::INVESTOR)
            ->where('group_id', $groupId)
            ->get();

        $investorIncomes   = collect();
        $totalSale         = 0;
        $totalRecovered    = 0;
        $totalExpense      = 0;
        $totalGrossProfit  = 0;
        $totalShopProfit   = 0;
        $netProfit         = 0;

        if ($request->has('searchPerson')) {

            list($year, $month) = explode('-', $request->selectedMonth);
            $installments = Installment::with('booking')
                ->where('group_id', $groupId)
                ->whereYear('created_at', $year)
                ->whereMonth('created_at', $month)
                ->get();
            $bookings = Booking::where('group_id', $groupId)
                ->whereYear('deal_date', $year)
                ->whereMonth('deal_date', $month)
                ->get();

            $totalSale = $bookings->sum('total_payable');
            $totalRecovered = $installments->sum('paid_amount');
            $totalExpense = Transaction::where('group_id', $groupId)
                ->where('type', Transaction::TYPE_DEBIT)
                ->whereYear('created_at', $year)
                ->whereMonth('created_at', $month)
                ->whereHas('account', fn($q) => $q->where('type', Account::EXPENSE))
                ->sum('amount');
            $bookingProfits = [];

            foreach ($installments as $inst) {

                $booking = $inst->booking;

                if (!$booking) continue;

                $purchaseCost = Purchase::where('id', $booking->purchase_id)->value('cost_price') ?? 0;
                $totalMonths  = $booking->total_months ?: 1;

                $monthlyProfit = $inst->paid_amount - ($purchaseCost / $totalMonths);

                if ($monthlyProfit > 0) {
                    $totalGrossProfit += $monthlyProfit;

                    if (!isset($bookingProfits[$booking->id])) {
                        $bookingProfits[$booking->id] = 0;
                    }

                    $bookingProfits[$booking->id] += $monthlyProfit;
                }
            }
            $netProfit = $totalGrossProfit - $totalExpense;
            $endOfMonth = Carbon::create($year, $month, 1)->endOfMonth();

            $totalGroupInvestment = Investment::where('group_id', $groupId)
                ->whereDate('investment_date', '<=', $endOfMonth)
                ->sum('amount');

            if ($totalGroupInvestment <= 0) $totalGroupInvestment = 1;
            foreach ($investors as $inv) {
                $investorJoinDate = Investment::where('group_id', $groupId)
                    ->where('account_id', $inv->id)
                    ->min('investment_date');
                $investorTotalInvestment = Investment::where('group_id', $groupId)
                    ->where('account_id', $inv->id)
                    ->whereDate('investment_date', '<=', $endOfMonth)
                    ->sum('amount');
                // if ($investorTotalInvestment <= 0) continue;
                $eligibleBookingProfits = collect($bookingProfits)
                    ->filter(function ($profit, $bookingId) use ($investorJoinDate) {
                        $booking = Booking::find($bookingId);
                        return $booking && $booking->deal_date >= $investorJoinDate;
                    });

                $eligibleGrossProfit = $eligibleBookingProfits->sum();

                if ($eligibleGrossProfit <= 0) continue;
                $investorNetProfit = ($investorTotalInvestment / $totalGroupInvestment) * $netProfit;
                if ($inv->is_business) {
                    $investorIncome = $investorNetProfit;
                    $shopIncome = 0;
                } else {
                    $investorIncome = $investorNetProfit * 0.5;
                    $shopIncome     = $investorNetProfit * 0.5;
                }

                $totalShopProfit += $shopIncome;
                $investorIncomes->push((object)[
                    'investor_id'        => $inv->id,
                    'investor_name'      => $inv->name,
                    'month'              => "$month-$year",
                    'total_investment'   => $investorTotalInvestment,
                    'monthly_profit'     => $investorNetProfit,
                    'investorIncome'     => $investorIncome,
                    'shopProfit'         => $shopIncome,
                ]);
            }
        }

        return view('investorIncomeReport.index', compact(
            'investorIncomes',
            'user',
            'investors',
            'totalExpense',
            'totalSale',
            'totalRecovered',
            'totalGrossProfit',
            'netProfit',
            'totalShopProfit'
        ));
    }

    public function cashInOutSummary(Request $request)
    {
        $user = Session::get('user');
        $groupId = $user->group_id;
        $startDate = $request->input('start_date', now()->toDateString());
        $endDate = $request->input('end_date', now()->toDateString());
        if ($request->filled(['start_date', 'end_date'])) {
            $request->validate([
                'start_date' => 'required|date',
                'end_date' => 'required|date|after_or_equal:start_date',
            ]);
        }
        $startDateTime = Carbon::parse($startDate)->startOfDay();
        $endDateTime = Carbon::parse($endDate)->endOfDay();

        $cashIn = Transaction::where('group_id', $groupId)
            ->where('type', Transaction::TYPE_CREDIT)
            ->whereBetween('created_at', [$startDateTime, $endDateTime])
            ->sum('amount');

        $cashOut = Transaction::where('group_id', $groupId)
            ->where('type', Transaction::TYPE_DEBIT)
            ->whereBetween('created_at', [$startDateTime, $endDateTime])
            ->sum('amount');

        $net = $cashIn - $cashOut;

        $totalSales = Booking::where('group_id', $groupId)
            ->whereBetween('deal_date', [$startDateTime, $endDateTime])
            ->sum('total_payable');

        $transactions = Transaction::with('account')
            ->where('group_id', $groupId)
            ->whereBetween('created_at', [$startDateTime, $endDateTime])
            ->orderBy('created_at', 'desc')
            ->get();
        $debit = Transaction::TYPE_DEBIT;
        return view('cashSummary.index', compact('cashIn', 'cashOut', 'net', 'totalSales', 'startDate', 'endDate', 'user', 'transactions', 'debit'));
    }
    public function itemWiseProfitReport(Request $request)
    {
        $user = Session::get('user');
        $groupId = $user->group_id;

        $summary = null;
        $productId = $request->input('product_id', 'all');

        $startDate = $request->input('start_date', now()->subMonth()->toDateString());
        $endDate   = $request->input('end_date', now()->toDateString());

        // Validate dates only when provided
        if ($request->filled(['start_date', 'end_date'])) {
            $request->validate([
                'start_date' => 'required|date',
                'end_date'   => 'required|date|after_or_equal:start_date',
            ]);
        }

        // Load all products
        $products = Product::where('group_id', $groupId)
            ->orderBy('product_name')
            ->get();

        $reportData = collect();

        if ($request->has('generate_report')) {

            $startDateTime = Carbon::parse($startDate)->startOfDay();
            $endDateTime   = Carbon::parse($endDate)->endOfDay();

            // Main Query
            $bookingsQuery = Booking::with([
                'product:id,product_name,product_company',
                'purchase:id,cost_price'
            ])
                ->where('group_id', $groupId)
                ->whereBetween('deal_date', [$startDateTime, $endDateTime])
                ->whereNotNull('purchase_id');

            if ($productId !== 'all') {
                $bookingsQuery->where('product_id', $productId);
            }

            $bookings = $bookingsQuery->get();
            $groupedData = $bookings->groupBy('product_id')->map(function ($productBookings) {

                $product = $productBookings->first()->product;
                $totalSales = $productBookings->sum('total_payable');
                $totalCost = $productBookings->sum(function ($booking) {
                    $cost = $booking->purchase->cost_price ?? 0;
                    $qty = $booking->quantity ?? 1;
                    return $cost * $qty;
                });

                $totalProfit = $totalSales - $totalCost;
                $profitPercentage = $totalCost > 0
                    ? ($totalProfit / $totalCost) * 100
                    : 0;
                $quantitySold = $productBookings->sum('quantity') ?: $productBookings->count();

                return (object) [
                    'product_id' => $product->id,
                    'product_name' => $product->product_name,
                    'product_company' => $product->product_company,

                    'quantity_sold' => $quantitySold,
                    'total_sales' => $totalSales,
                    'total_cost' => $totalCost,
                    'total_profit' => $totalProfit,
                    'profit_percentage' => $profitPercentage,

                    'average_sale_price' => $quantitySold > 0 ? $totalSales / $quantitySold : 0,
                    'average_cost_price' => $quantitySold > 0 ? $totalCost / $quantitySold : 0,
                ];
            });

            $reportData = $groupedData->values();
            $summary = (object) [
                'total_sales'   => $reportData->sum('total_sales'),
                'total_cost'    => $reportData->sum('total_cost'),
                'total_profit'  => $reportData->sum('total_profit'),
                'total_quantity' => $reportData->sum('quantity_sold'),
                'average_profit_margin' => 0
            ];

            if ($summary->total_cost > 0) {
                $summary->average_profit_margin =
                    ($summary->total_profit / $summary->total_cost) * 100;
            }
        }

        return view('reports.itemWiseProfit', compact(
            'products',
            'productId',
            'startDate',
            'endDate',
            'reportData',
            'summary',
            'user'
        ));
    }

    public function thermalPrint(Request $request, $booking_id)
    {
        $user = Session::get('user');
        $booking = Booking::with([
            'account.guarantors',
            'account.customerDocuments',
            'product',
            'installments.updatedBy',
            'installments.paymentLogs'
        ])
            ->where('id', $booking_id)
            ->where('group_id', $user->group_id)
            ->firstOrFail();
        $totalPayable = $booking->total_payable ?? 0;
        $totalPaid = $booking->installments->sum('paid_amount') ?? 0;
        $outstanding = $totalPayable - $totalPaid;
        $penalty = $booking->installments->sum('late_payment_penalty') ?? 0;
        $receivedPercentage = $totalPayable > 0 ? round(($totalPaid / $totalPayable) * 100, 2) : 0;

        $booking->payment_details = (object) [
            'net_amount' => $totalPayable,
            'received_amount' => $totalPaid,
            'outstanding_amount' => $outstanding,
            'penalty_amount' => $penalty,
            'received_percentage' => $receivedPercentage,
        ];
        $booking->deal_date_formatted = $booking->deal_date ? Carbon::parse($booking->deal_date)->format('d/M/Y') : 'N/A';

        return view('contractDetails.thermalPrint', compact('booking', 'user'));
    }
    public function printA5(Request $request, $booking_id)
    {
        $user = Session::get('user');
        $booking = Booking::with([
            'account.guarantors',
            'account.customerDocuments',
            'product',
            'installments.updatedBy',
            'installments.paymentLogs'
        ])
            ->where('id', $booking_id)
            ->where('group_id', $user->group_id)
            ->firstOrFail();
        $totalPayable = $booking->total_payable ?? 0;
        $totalPaid = $booking->installments->sum('paid_amount') ?? 0;
        $outstanding = $totalPayable - $totalPaid;
        $penalty = $booking->installments->sum('late_payment_penalty') ?? 0;
        $receivedPercentage = $totalPayable > 0 ? round(($totalPaid / $totalPayable) * 100, 2) : 0;

        $booking->payment_details = (object) [
            'net_amount' => $totalPayable,
            'received_amount' => $totalPaid,
            'outstanding_amount' => $outstanding,
            'penalty_amount' => $penalty,
            'received_percentage' => $receivedPercentage,
        ];
        return view('contractDetails.printA5', compact('booking', 'user'));
    }

    public function businessReport(Request $request)
    {
        $user = Session::get('user');
        $groupId = $user->group_id;
        $startDate = $request->input('start_date', now()->toDateString());
        $endDate = $request->input('end_date', now()->toDateString());

        if ($request->filled(['start_date', 'end_date'])) {
            $request->validate([
                'start_date' => 'required|date',
                'end_date' => 'required|date|after_or_equal:start_date',
            ]);
        }

        $startDateTime = Carbon::parse($startDate)->startOfDay();
        $endDateTime = Carbon::parse($endDate)->endOfDay();

        // Total Investment of Investors
        $totalInvestment = Investment::where('group_id', $groupId)
            ->whereBetween('investment_date', [$startDateTime, $endDateTime])
            ->sum('amount');
        $totalPurchases = Purchase::where('group_id', $groupId)
            ->whereBetween('purchase_date', [$startDateTime, $endDateTime])
            ->sum('total_price');
        $availableStock = Purchase::where('group_id', $groupId)
            ->where('quantity', '>', 0)
            ->whereBetween('purchase_date', [$startDateTime, $endDateTime])
            ->count();
        $totalStockQuantity = Purchase::where('group_id', $groupId)
            ->where('quantity', '>', 0)
            ->whereBetween('purchase_date', [$startDateTime, $endDateTime])
            ->sum('quantity');
        $totalStockValue = Purchase::where('group_id', $groupId)
            ->where('quantity', '>', 0)
            ->whereBetween('purchase_date', [$startDateTime, $endDateTime])
            ->sum('total_price');
        $totalBookings = Booking::where('group_id', $groupId)
            ->whereBetween('deal_date', [$startDateTime, $endDateTime])
            ->count();

        $totalSales = Booking::where('group_id', $groupId)
            ->whereBetween('deal_date', [$startDateTime, $endDateTime])
            ->sum('total_payable');
        $installments = Installment::with('booking')
            ->where('group_id', $groupId)
            ->whereBetween('created_at', [$startDateTime, $endDateTime])
            ->get();
        $totalRecoveries  = $installments->sum('paid_amount');
        $purchases = Purchase::with(['product:id,product_name,product_company', 'account:id,name,type'])
            ->where('group_id', $groupId)
            ->whereBetween('purchase_date', [$startDateTime, $endDateTime])
            ->orderBy('created_at', 'desc')
            ->get();

        $bookings = Booking::with(['account:id,name,contact', 'product:id,product_name,product_company'])
            ->where('group_id', $groupId)
            ->whereBetween('deal_date', [$startDateTime, $endDateTime])
            ->orderBy('deal_date', 'desc')
            ->get();

        $recoveries = SaleRecovery::with(['recoveryMan:id,name,contact'])
            ->where('group_id', $groupId)
            ->whereBetween('created_at', [$startDateTime, $endDateTime])
            ->orderBy('created_at', 'desc')
            ->get();

        $investments = Investment::with(['account:id,name,contact'])
            ->where('group_id', $groupId)
            ->whereBetween('investment_date', [$startDateTime, $endDateTime])
            ->orderBy('investment_date', 'desc')
            ->get();
        $totalExpense = Transaction::where('group_id', $groupId)
            ->where('type', Transaction::TYPE_DEBIT)
            ->whereBetween('created_at', [$startDateTime, $endDateTime])
            ->whereHas('account', fn($q) => $q->where('type', Account::EXPENSE))
            ->sum('amount');


        return view('reports.businessReport', compact(
            'totalInvestment',
            'totalPurchases',
            'availableStock',
            'totalStockQuantity',
            'totalStockValue',
            'totalBookings',
            'totalSales',
            'totalRecoveries',
            'startDate',
            'endDate',
            'purchases',
            'bookings',
            'recoveries',
            'investments',
            'user',
            'totalExpense'
        ));
    }
}
