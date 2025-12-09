<?php

namespace App\Http\Controllers;

use App\Models\RoleScreen;
use App\Models\SoftwareScreen;
use App\Models\UserScreenTab;
use App\Services\ActionLogService;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Spatie\Permission\Models\Role;

class RoleScreenController extends Controller
{
    public function assign(Request $request): RedirectResponse
    {
        $request->validate([
            'role_id' => 'required',
            'screen_id' => 'required',
        ]);

        $groupId = Session::get('user')->group_id;
        $roleId = $request->input('role_id');
        $screenId = $request->input('screen_id');
        $expiryDate = $request->input('expiry_date') ?? Carbon::now()->addYear()->format('Y-m-d');
        $canWrite = $request->boolean('can_write');

        $role = Role::find($roleId);
        $childScreen = SoftwareScreen::find($screenId);

        $roleName = $role->name;
        $screenName = $childScreen->screen_name;
        $parentId = $childScreen->parent_id;

        if (RoleScreen::where('role_id', $roleId)->where('screen_id', $screenId)->exists()) {
            return redirect()->route('roleManagement.index')
                ->with('error', "Screen $screenName is already assigned to $roleName.");
        }

        $assignChildScreen = RoleScreen::create([
            'group_id' => $groupId,
            'role_id' => $roleId,
            'screen_id' => $screenId,
            'expiry_date' => $expiryDate,
            'can_write' => $canWrite,
        ]);

        RoleScreen::firstOrCreate(
            [
                'role_id' => $roleId,
                'screen_id' => $parentId,
            ],
            [
                'group_id' => $groupId,
                'expiry_date' => $expiryDate,
                'can_write' => $canWrite,
            ]
        );

        return redirect()->route('roleManagement.index')
            ->with(
                $assignChildScreen ? 'success' : 'error',
                $assignChildScreen ? "Screen $screenName assigned successfully to $roleName." : 'Permission not assigned.'
            );
    }

    public function assignTab(Request $request): RedirectResponse
    {
        $request->validate([
            'user_id' => 'required',
            'screen_id' => 'required',
            'tab_id' => 'required',
        ]);

        $groupId = Session::get('user')->group_id;
        $userId = $request->input('user_id');
        $tabIds = $request->input('tab_id');
        $status = UserScreenTab::ACTIVE;

        foreach ($tabIds as $tabId) {
            $assignTab = UserScreenTab::firstOrCreate([
                'user_id' => $userId,
                'screen_tab_id' => $tabId,
            ], [
                'group_id' => $groupId,
                'user_id' => $userId,
                'screen_tab_id' => $tabId,
                'status' => $status,
            ]);
        }

        return redirect()->route('roleManagement.index')
            ->with(
                $assignTab ? 'success' : 'error',
                $assignTab ? 'Tab assigned successfully' : 'Tab permission not assigned.'
            );
    }

    public function unassignTab($id)
    {
        $userTab = UserScreenTab::find($id);
        $rolePayload = $userTab->toArray();
        $userTab->delete();

        return redirect()
            ->route('roleManagement.index')
            ->with('success', 'Tab Unassigned Successfuly');
    }
}
