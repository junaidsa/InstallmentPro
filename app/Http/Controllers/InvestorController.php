<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Investment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class InvestorController extends Controller
{
    public function index()
    {
        $user = Session::get('user');
        $groupId = Session::get('user')->group_id;
        $accounts = Account::where('group_id', $groupId)->where('type', Account::INVESTOR)->get();
        $businessAccount =   Account::BUSINESS_ACCOUNT;
        return view('investorManagement.index', compact('accounts', 'user', 'businessAccount'))->with('i');
    }
    public function store(Request $request)
    {
        $groupId = Session::get('user')->group_id;

        $request->validate([
            'name'    => 'required|string|max:255',
            'contact' => 'required',
            'cnic' => 'required',
            'amount'  => 'required|numeric|min:1',
            'investment_date' => 'required|date',
        ]);
        $account = new Account([
            'group_id'          => $groupId,
            'name'              => $request->input('name'),
            'type'              => Account::INVESTOR,
            'investment_amount' => $request->input('amount'),
            'address'           => $request->input('address'),
            'contact'           => $request->input('contact'),
            'cnic'           => $request->input('cinic'),
            'total_investment'   => $request->input('amount'),
        ]);
        $account->save();
        Investment::create([
            'group_id'       => $groupId,
            'account_id'     => $account->id,
            'investor_name'  => $request->input('name'),
            'amount'         => $request->amount ?? 0,
            'investment_date' => $request->investment_date,
            'total_amount' => $request->amount ?? 0,
        ]);

        if ($account) {
            return redirect()->back()->with(
                'success',
                $account->type . ' ' . $account->name . ' Created Successfully'
            );
        } else {
            return redirect()->back()->with(
                'error',
                $account->type . ' ' . $account->name . ' Could Not Be Created Successfully'
            );
        }
    }
}
