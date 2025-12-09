<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class PurchaseController extends Controller
{
    public function index()
    {
        $user = Session::get('user');
        $types = Product::types();
        $suppliers = Account::where([
            ['type', Account::SUPPLIER],
            ['group_id', $user->group_id]
        ])->get();
        $products = Product::where('group_id', $user->group_id)->get();
        $purchases = Purchase::with('product', 'account')->where('group_id', $user->group_id)->get();
        return view('purchase.index', compact('user', 'suppliers', 'products', 'purchases', 'types'));
    }

    public function store(Request $request)
    {
        try {
            $user = Session::get('user');
            $groupId  = $user->group_id;
            $request->validate([
                'account_id' => 'required|exists:accounts,id',
                'product_type' => 'required',
                'product_id' => 'required|exists:products,id',
                'quantity' => 'required|integer|min:1',
                'purchase_date' => 'required|date',
            ]);

            Purchase::create([
                'group_id' => $user->group_id,
                'account_id' => $request->account_id,
                'product_type' => $request->product_type,
                'product_id' => $request->product_id,
                'quantity' => $request->quantity,
                'cost_price' => $request->cost_price,
                'sale_price' => $request->sale_price,
                'total_price' => $request->total_price,
                'purchase_date' => Carbon::parse($request->purchase_date)->format('Y-m-d'),
                'quantity_log' => $request->quantity,
            ]);
            Transaction::create([
                'group_id'   => $groupId,
                'account_id' => $request->account_id,
                'type'       => Transaction::TYPE_DEBIT,
                'amount'     => $request->total_price,
                'remarks'    => Transaction::PURCHASE_REMARKS,
                'narration'  => 'Purchase Amount Debit ' . now()->format('F Y'),
            ]);
            $shopAccount = Account::where('group_id', $groupId)
                ->where('name', Account::SHOP)
                ->firstOrFail();
            $shopAccount->decrement('balance', $request->total_price);
        } catch (\Exception $e) {
            Log::error('Error purchase Create: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);
            return redirect()->route('purchase.index')->with('error', 'An error occurred while recording the purchase: ' . $e->getMessage());
        }

        return redirect()->route('purchase.index')->with('success', 'Purchase recorded successfully.');
    }
    public function update(Request $request)
    {
        $request->validate([]);
        if ($request->ajax()) {
            Purchase::find($request->pk)->update([
                $request->name => $request->value,
            ]);

            $name = $request->name;
            $formatName = ucwords(str_replace('_', ' ', $name));
            $value = $request->value;

            return response()->json(['name' => $formatName, 'value' => $value]);
        }
    }

    public function delete($id)
    {
        $purchase = Purchase::find($id);

        if (!$purchase) {
            return redirect()
                ->route('purchase.index')
                ->with('error', $purchase->product->product_type . ' ' . $purchase->product->product_name . ' Product Not Found.');
        }
        $purchase->delete();
        return redirect()
            ->route('purchase.index')
            ->with('success',  $purchase->product->product_type . ' ' . $purchase->product->product_name . ' Deleted successfully.');
    }

    public function getProduct($type)
    {
        $products = Purchase::with('product')->where('product_type', $type)->get();
        return response()->json($products);
    }
}
