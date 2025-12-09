<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Blacklist;
use App\Models\CustomerDocument;
use App\Models\Investment;
use App\Services\GuarantorService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\View\View;

class AccountController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $user = Session::get('user');
        $groupId = Session::get('user')->group_id;
        $accounts = Account::active()->where('group_id', $groupId)->where('type', '!=', Account::INVESTOR)->get();
        return view('accountManagement.index', compact('accounts', 'user'))->with('i');
    }
    public function store(Request $request)
    {
        try {
            $groupId = Session::get('user')->group_id;
            $stationId = Session::get('user')->station_id;

            $request->validate([
                'name' => 'required|string|max:255',
                'type' => 'required|string',
                'cnic_front' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
                'cnic_back' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
                'document' => 'nullable',
                'image' => 'nullable',
            ]);

            $accounts = new Account([
                'group_id' => $groupId,
                'station_id' => $stationId,
                'name' => $request->input('name'),
                'father_name' => $request->input('father_name'),
                'type' => $request->input('type'),
                'cnic' => $request->input('cnic'),
                'email' => $request->input('email'),
                'address' => $request->input('address'),
                'contact' => $request->input('contact'),
                'account_no' => $request->input('account_no'),
                'investment_amount' => $request->input('investment_amount'),
                'balance' => $request->input('balance') ?? 0,
                'designation' => $request->input('designation'),
                'wage_type' => $request->input('wage_type'),
                'wage' => $request->input('wage'),
            ]);
            $accounts->save();
            if ($accounts->type == Account::CUSTOMER) {
                $documentTypes = ['cnic_front', 'cnic_back', 'document', 'image'];

                foreach ($documentTypes as $type) {
                    if ($request->hasFile($type)) {
                        $media = $request->file($type);
                        $mediaName = time() . '_' . uniqid() . '.' . $media->getClientOriginalExtension();
                        $destination = public_path(Account::CUSTOMER_MEDIA);
                        $media->move($destination, $mediaName);

                        CustomerDocument::create([
                            'group_id'      => $groupId,
                            'customer_id'   => $accounts->id,
                            'document_type' => $type,
                            'document_path' => Account::CUSTOMER_MEDIA . '/' . $mediaName,
                        ]);
                    }
                }

                $guarantorService = new GuarantorService();
                $guarantor = $guarantorService->store(
                    $request->guarantors,
                    $accounts->id,
                    $groupId
                );
            }

            if ($request->ajax()) {
                return response()->json([
                    'data' => $accounts,
                ]);
            } else {

                if ($accounts) {
                    return redirect()->back()->with('success', $accounts->account_type . ' ' . $accounts->name . ' Created Successfuly');
                }

                return redirect()->back()->with('error', $accounts->account_type . ' ' . $accounts->name . ' Could Not Created Successfuly');
            }
        } catch (\Exception $e) {
            Log::error('Error Account Created issue: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);
            return redirect()->back()->with('error', 'Validation Error: ' . $e->getMessage());
        }
    }

    public function update(Request $request)
    {
        $account = Account::findOrFail($request->pk);

        try {
            $rules = [];
            if ($request->name === 'cnic') {
                if ($account->type === Account::CUSTOMER) {
                    $rules['value'] = 'nullable|string|unique:accounts,cnic,' . $account->id;
                } else {
                    $rules['value'] = 'nullable|string';
                }
            }
            if ($request->name === 'type' && $request->value === Account::CUSTOMER) {
                if (Account::where('cnic', $account->cnic)->where('id', '!=', $account->id)->exists()) {
                    return response()->json(['message' => 'CNIC has already been taken.'], 422);
                }
            }

            if (!empty($rules)) {
                $request->validate($rules);
            }
            $account->update([
                $request->name => $request->value,
            ]);
            return response()->json(['message' => 'Updated successfully']);
        } catch (\Exception $e) {
            Log::error('Error Account Created issue: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);
        }
    }




    /**
     * Remove the specified resource from storage.
     */
    public function delete($id)
    {

        $account = Account::find($id);

        if (!$account) {
            return redirect()
                ->route('accountManagement.index')
                ->with('error', $account->account_type . ' ' . $account->name . ' Account Not Found.');
        }

        if ($account->type === Account::INVESTOR) {
            return redirect()
                ->route('accountManagement.index')
                ->with('error', 'Investor accounts cannot be deleted.');
        }
        $account->delete();

        return redirect()
            ->route('accountManagement.index')
            ->with('success', $account->account_type . ' ' . $account->name . ' Deleted successfully.');
    }

    public function show($id)
    {
        $account = Account::find($id);

        return view('account.index', compact('account'))->with('i');
    }

    public function toggleCustomerStatus($id)
    {
        try {
            $account = Account::findOrFail($id);

            if ($account->status === Account::ACTIVE) {
                $account->status = Account::BLOCK;
                $account->save();
                Blacklist::create([
                    'account_id' => $account->id,
                    'station_id' => $account->station_id,
                    'group_id' => $account->group_id,
                    'name'       => $account->name,
                    'cnic'       => $account->cnic,
                    'contact'    => $account->contact,
                    'address'    => $account->address,
                    'notes'      => 'Customer blocked on ' . now(),
                ]);

                $message = 'Customer has been blocked and added to blacklist successfully.';
            } else {
                $account->status = Account::ACTIVE;
                $account->save();
                Blacklist::where('account_id', $account->id)->delete();

                $message = 'Customer has been activated successfully.';
            }

            return redirect()->back()->with('success', $message);
        } catch (\Exception $e) {
            Log::error('Error toggleCustomerStatus function: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);
        }
    }


    public function blackList()
    {
        $user = Session::get('user');
        $blackList = Blacklist::where([
            'group_id'   => $user->group_id,
        ])
            ->get();
        return view('blackList.index', compact('user', 'blackList'));
    }
    public function investorStore(Request $request)
    {
        try {
            $groupId = Session::get('user')->group_id;

            $request->validate([
                'name'    => 'required|string|max:255',
                'type'    => 'required|string',
                'contact' => 'required',
                'amount'  => 'required|numeric|min:1',
                'investment_date' => 'nullable|date',
            ]);
            $account = new Account([
                'group_id'          => $groupId,
                'name'              => $request->input('name'),
                'type'              => $request->input('type'),
                'investment_amount' => $request->input('amount'),
                'address'           => $request->input('address'),
                'contact'           => $request->input('contact'),
                'total_investment'   => $request->input('contact'),
            ]);
            $account->save();
            Investment::create([
                'group_id'       => $groupId,
                'account_id'     => $account->id,
                'investor_name'  => $request->input('name'),
                'amount'         => $request->amount ?? 0,
                'investment_date' => $request->investment_date ?? now(),
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
        } catch (\Exception $e) {
            Log::error('Error investorStore function: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);
        }
    }

    public function checkCnic(Request $request)
    {
        $exists = Account::where('cnic', $request->cnic)
            ->where('type', \App\Models\Account::CUSTOMER)
            ->exists();
        return response()->json(['exists' => $exists]);
    }


    public function getGuarantor($id)
    {
        try {
            $guarantors = GuarantorService::getByCustomerId($id);
            return response()->json(['guarantors' => $guarantors]);
        } catch (\Exception $e) {
            Log::error('Error getGuarantor leave: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);
        }
    }
    public function updateGuarantor(Request $request)
    {
        try {
            $guarantorService = new GuarantorService();
            $guarantorsData = $request->input('guarantors', []);
            $guarantorsFiles = $request->file('guarantors', []);
            $guarantorService->update($guarantorsData, $guarantorsFiles);
            return response()->json(['message' => 'Guarantors updated successfully.']);
        } catch (\Exception $e) {
            Log::error('Error updating guarantor: ' . $e->getMessage());
            return response()->json(['error' => 'Something went wrong, please try again later.'], 500);
        }
    }

    public function getCustomerDocuments($id)
    {
        try {
            $documents = CustomerDocument::where('customer_id', $id)->get();
            return response()->json(['documents' => $documents]);
        } catch (\Exception $e) {
            Log::error('Error fetching customer documents: ' . $e->getMessage());
            return response()->json(['error' => 'Something went wrong, please try again later.'], 500);
        }
    }

    public function updateCustomerDocuments(Request $request)
    {
        $groupId = Session::get('user')->group_id;

        $request->validate([
            'customer_id' => 'required|exists:accounts,id',
            'cnic_front' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'cnic_back' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'document' => 'nullable|mimes:pdf|max:5120',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        try {
            $customerId = $request->input('customer_id');
            $documentTypes = Account::DOCUMENT_TYPES;

            foreach ($documentTypes as $type) {
                if ($request->hasFile($type)) {
                    $existingDoc = CustomerDocument::where('customer_id', $customerId)
                        ->where('document_type', $type)
                        ->first();

                    if ($existingDoc) {
                        if (file_exists(public_path($existingDoc->document_path))) {
                            unlink(public_path($existingDoc->document_path));
                        }
                        $existingDoc->delete();
                    }
                    $media = $request->file($type);
                    $mediaName = time() . '_' . uniqid() . '.' . $media->getClientOriginalExtension();
                    $destination = public_path(Account::CUSTOMER_MEDIA);
                    $media->move($destination, $mediaName);
                    CustomerDocument::create([
                        'group_id' => $groupId,
                        'customer_id' => $customerId,
                        'document_type' => $type,
                        'document_path' => Account::CUSTOMER_MEDIA . '/' . $mediaName,
                    ]);
                }
            }

            return response()->json(['message' => 'Documents updated successfully.']);
        } catch (\Exception $e) {
            Log::error('Error updating customer documents: ' . $e->getMessage());
            return response()->json(['error' => 'Something went wrong, please try again later.'], 500);
        }
    }
}
