<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Services\GroupService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\ValidationException;

class GroupController extends Controller
{
    const ACTIVE_STATUS = 1;

    protected $groupService;

    public function __construct(GroupService $groupService)
    {
        $this->groupService = $groupService;
    }

    public function index()
    {
        $adminRole = Session::get('user')->role;
        if ($adminRole == Group::SUPER_ADMIN) {
            $activeStatus = self::ACTIVE_STATUS;
            $groups = Group::all();

            return view('groupManagement.index', compact('groups', 'activeStatus'));
        }

        return redirect('/restricted')->with('error', 'Access not granted.');
    }

    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'name' => 'required',
                'city' => 'required',
                'address' => 'required',
                'contact' => 'required',
                'fee' => 'required',
                'billing_cycle' => 'required',
                'company_type' => 'required',
                'logo' => 'nullable',
            ]);

            $this->groupService->store($validatedData);

            return redirect()->route('groupManagement')->with('success', 'Group created successfully');
        } catch (ValidationException $e) {
            return redirect()->route('groupManagement')->with('error', $e->getMessage());
        } catch (\Exception $e) {
            Log::error('Error creating group: '.$e->getMessage());

            return redirect()->route('groupManagement')->with('error', 'An error occurred while creating the group.');
        }
    }

    public function assign(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'name' => 'required',
                'user_name' => 'required',
                'email' => 'required',
                'password' => 'required',
                'group_id' => 'required',
            ]);

            $this->groupService->assign($validatedData);

            return redirect()->route('groupManagement')->with('success', 'Group assigned successfully');
        } catch (ValidationException $e) {
            return redirect()->route('groupManagement')->with('error', $e->getMessage());
        } catch (\Exception $e) {
            \Log::error('Error assigning group: '.$e->getMessage());

            return redirect()->route('groupManagement')->with('error', 'An error occurred while assigning the group.');
        }
    }

    public function update(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required',
                'value' => 'required',
            ]);

            $groupId = $request->pk;
            $name = $request->name;
            $value = $request->value;

            $data = $this->groupService->update($groupId, $name, $value);

            return response()->json($data);
        } catch (ValidationException $e) {
            return response()->json(['error' => $e->getMessage()]);
        } catch (\Exception $e) {
            \Log::error('Error:'.$e->getMessage());

            return response()->json(['error' => 'Unexpected error occured while updating data.']);
        }

    }

    public function inActive($groupId)
    {
        try {
            $data = $this->groupService->inActive($groupId);

            return redirect()->route('groupManagement')
                ->with('success', 'Group "'.$data['name'].'" is now '.$data['status'].' successfully.');
        } catch (\Exception $e) {
            \Log::error('Error: '.$e->getMessage());

            return redirect()->route('groupManagement')->with('error', 'Unexpected error occurred while updating data.');
        }
    }

    public function updateCycle($groupId)
    {
        try {
            $this->groupService->updateCycle($groupId);

            return redirect()->route('groupManagement')
                ->with('success', 'Group trial expiry has been extended successfully.');
        } catch (\Exception $e) {
            \Log::error('Error: '.$e->getMessage());

            return redirect()->route('groupManagement')->with('error', 'Unexpected error occurred while extending trial expiry.');
        }
    }
}
