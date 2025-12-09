<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\View\View;

class SaleReportController extends Controller
{
    public function index(Request $request): View
    {
        $user = Session::get('user');
        $groupId = $user->group_id;
        $types = Product::types();
        $products = Product::where('group_id', $groupId)->get();

        $sales = collect();
        $totalSales = 0;

        if ($request->filled(['start_date', 'end_date'])) {
            $request->validate([
                'start_date' => 'required|date',
                'end_date' => 'required|date|after_or_equal:start_date',
            ]);

            $query = Booking::where('group_id', $groupId)
                ->whereBetween('created_at', [
                    Carbon::parse($request->input('start_date'))->startOfDay(),
                    Carbon::parse($request->input('end_date'))->endOfDay()
                ])
                ->with('product', 'account');

            if ($request->filled('product_type')) {
                $query->whereHas('product', function ($q) use ($request) {
                    $q->where('product_type', $request->input('product_type'));
                });
            }

            if ($request->filled('product_id')) {
                $query->where('product_id', $request->input('product_id'));
            }

            $sales = $query->orderBy('created_at')
                ->get()
                ->groupBy(function ($booking) {
                    return Carbon::parse($booking->created_at)->format('Y-m-d');
                });

            $totalSales = $sales->flatten()->sum('total_amount');
        }

        return view('saleReport.index', compact('sales', 'totalSales', 'user', 'products', 'types'));
    }
}
