<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Transaction;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\View\View;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $user = Session::get('user');
        $groupId = Session::get('user')->group_id;
        $accounts = Account::all()->whereNull('deleted_at')->where('group_id', $groupId);
        $types = Account::types();

        return view('transactions.index', compact('accounts', 'user', 'types'))
            ->with('i');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'account_id' => 'required|exists:accounts,id',
                'amount'     => 'required|numeric|min:0',
                'type'       => 'required|in:Debit,Credit',
                'method'     => 'required|string',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:40960',
            ]);

            $accountId = $request->input('account_id');
            $transactionType = $request->input('type');
            $transactionAmount = $request->input('amount');

            if ($transactionType === Transaction::TYPE_DEBIT) {
                $transactionAmount = -$transactionAmount;
            }
            $imagePath = null;
            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $destination = public_path(Transaction::TRANSACTION_MEDIA);
                if (!file_exists($destination)) {
                    mkdir($destination, 0755, true);
                }
                $mediaName = time() . '_' . $file->getClientOriginalName();
                $file->move($destination, $mediaName);
                $imagePath = $mediaName;
            }

            DB::transaction(function () use ($accountId, $transactionAmount, $request, $transactionType, $imagePath) {
                $user = Session::get('user');

                Account::where('id', $accountId)->update([
                    'balance' => DB::raw('balance + ' . $transactionAmount),
                ]);

                $updatedAccount = Account::findOrFail($accountId);
                $shopAccount = Account::where('name', Account::SHOP)
                    ->where('type', Account::EXPENSE)->first();

                if ($transactionType == Transaction::TYPE_DEBIT) {
                    $shopAccount->balance = $shopAccount->balance + $transactionAmount;
                    $shopAccount->save();
                }

                Transaction::create([
                    'group_id'   => $user->group_id,
                    'station_id' => $user->station_id,
                    'account_id' => $accountId,
                    'amount'     => $request->input('amount'),
                    'type'       => $transactionType,
                    'method'     => $request->input('method'),
                    'balance'    => $updatedAccount->balance,
                    'doc_no'     => $request->doc_no,
                    'narration'  => $request->narration,
                    'image'      => $imagePath,
                ]);
            });

            $accountType = Account::findOrFail($request->input('account_id'))->account_type;

            return redirect()->route('transactions.index')
                ->with('success', ucfirst($request->input('type')) . ' transaction recorded successfully for ' . ucfirst($accountType) . ' account.');
        } catch (\Exception $e) {
            Log::error('Error Account Created issue: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);

            return back()->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }

    public function getAccountTransaction($id)
    {

        $groupId = Session::get('user')->group_id;
        $data = Transaction::with('account')->where('account_id', $id)->where('group_id', $groupId)->orderBy('created_at', 'desc')->take(10)->get();

        return response()->json($data);
    }
}
