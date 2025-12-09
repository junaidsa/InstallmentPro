<?php

namespace App\Http\Controllers;

use App\Models\RoleScreen;
use App\Models\SoftwareScreen;
use App\Models\User;
use App\Models\UserScreenTab;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Session;
use Illuminate\View\View;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    public function index(Request $request): View
    {
        $user = Session::get('user');
        $groupId = $user->group_id;
        $users = User::where('group_id', $groupId)->get();
        $roles = Role::where([['group_id', $groupId], ['name', '!=', Config::get('designationConstants.ADMIN')]])->orderBy('created_at', 'desc')->get();
        $roleScreens = RoleScreen::where('group_id', $groupId)->get();
        $screens = SoftwareScreen::with('screenTabs')->where(['group_id' => $groupId, 'is_parent' => SoftwareScreen::NOT_PARENT])->get();
        $userTabs = UserScreenTab::where('group_id', $groupId)->get();

        return view('roleManagement.index', compact('roles', 'roleScreens', 'screens', 'user', 'users', 'userTabs'))->with('i');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $user = Session::get('user');
        $request->validate([
            'name' => 'required',
        ]);

        $roles = new Role([
            'group_id' => $user->group_id,
            'name' => $request->input('name'),
            'created_by' => $user->id,
        ]);
        $roles->save();

        return redirect()
            ->route('roleManagement.index')
            ->with('success', 'Role ' . $roles->name . ' Created Successfuly');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $groupId = Session::get('user')->group_id;
        $request->validate([
            'name' => 'required',
        ]);

        if ($request->ajax()) {
            $name = $request->name;
            $formattedName = ucwords(str_replace('_', ' ', $name));
            $value = $request->value;

            if ($name === 'expiry_date') {
                $existingExpiryDate = RoleScreen::where('id', $request->pk)
                    ->where('expiry_date', $value)
                    ->exists();

                if ($existingExpiryDate) {
                    return response()->json(['status' => 'error', 'message' => $formattedName . ' ' . $value . ' ' . 'already exists.'], 403);
                }
            }
            RoleScreen::find($request->pk)->update([
                $name => $value,
            ]);

            return response()->json(['status' => 'success', 'name' => $formattedName, 'value' => $value]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function delete($id)
    {
        RoleScreen::where('role_id', $id)->delete();
        $role = Role::find($id);
        $role->delete();

        return redirect()
            ->route('roleManagement.index')
            ->with('success', 'Role ' . $role->name . ' Deleted Successfuly');
    }

    public function screenDelete($id)
    {
        try {
            $roleScreen = RoleScreen::findOrFail($id);
            $screenAssign = $roleScreen->softwareScreen->screen_name ?? 'Unknown Screen';
            $roleScreen->delete();

            return response()->json([
                'status' => true,
                'message' => 'Screen ' . $screenAssign . ' deleted successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Error deleting screen: ' . $e->getMessage(),
            ], 500);
        }
    }


    public function rolesData(Request $request)
    {
        $request->validate([
            'id' => 'required',
            'dataOnly' => 'required',
        ]);
        $response = null;

        if ($request->ajax()) {
            $userId = $request->id;
            $user = User::find($userId);

            $response = $user->roles->toArray();
        }

        return \json_encode($response);
    }
}
