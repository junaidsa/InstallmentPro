<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\RoleScreen;
use App\Models\ScreenTab;
use App\Models\SoftwareScreen;
use App\Models\UsersScreenArrangement;
use App\Services\AssignScreenService;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Session;
use Illuminate\View\View;

class SoftwareScreenController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $user = Session::get('user');
        $groupId = $user->group_id;
        $softwareScreens = SoftwareScreen::where('group_id', $groupId)->get();

        return view('screenManagement.index', compact('softwareScreens', 'user'))
            ->with('i');
    }

    public function store(Request $request): RedirectResponse
    {
        $groupId = Session::get('user')->group_id;
        $request->validate([
            'screen_name' => 'required',
            'directory' => 'required',
        ]);

        $softwareScreen = new SoftwareScreen([
            'screen_name' => $request->input('screen_name'),
            'permission_key' => $request->input('screen_name'),
            'directory' => $request->input('directory'),
            'parent_id' => $request->input('parent_id'),
            'is_parent' => $request->input('is_parent'),
            'group_id' => $groupId,
        ]);
        $softwareScreen->save();

        $screenId = $softwareScreen->id;
        $roleId = Role::where('name',  __('lang.ADMIN'))->value('id');
        $expiryDate = Carbon::now()->addYear()->format('Y-m-d');

        $roleScreen = new RoleScreen([
            'group_id' => $groupId,
            'role_id' => $roleId,
            'screen_id' => $screenId,
            'expiry_date' => $expiryDate,
            'can_write' => 1,
        ]);
        $roleScreen->save();

        return redirect()->route('screenManagement.index')
            ->with('success', 'Screen ' . $softwareScreen->screen_name . ' Created Successfuly');
    }

    public function tabStore(Request $request)
    {
        $groupId = Session::get('user')->group_id;
        $request->validate([
            'name' => 'required',
            'screen_id' => 'required',
        ]);

        $screentab = new ScreenTab([
            'name' => $request->input('name'),
            'screen_id' => $request->input('screen_id'),
            'group_id' => $groupId,
        ]);
        $screentab->save();
        return redirect()->route('screenManagement.index')
            ->with('success', 'Tab ' . $screentab->name . ' Created Successfuly');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $request->validate([]);

        if ($request->ajax()) {
            SoftwareScreen::find($request->pk)
                ->update([
                    $request->name => $request->value,
                ]);

            $name = $request->name;
            $formattedName = ucwords(str_replace('_', ' ', $name));
            $value = $request->value;

            return response()->json(['name' => $formattedName, 'value' => $value]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function delete($id)
    {
        $softwareScreen = SoftwareScreen::find($id);

        if (! $softwareScreen) {
            return redirect()->route('screenManagement.index')
                ->with('error', 'Screen not found.');
        }

        RoleScreen::where('screen_id', $id)->delete();
        $softwareScreen->delete();

        return redirect()->route('screenManagement.index')
            ->with('success', 'Screen ' . $softwareScreen->screen_name . ' Deleted Successfuly');
    }

    public function updateScreenArrangement(Request $request)
    {
        $user = Session::get('user');
        $userId = $user->id;
        $groupId = $user->group_id;

        foreach ($request->order as $item) {
            $arrangement = UsersScreenArrangement::updateOrCreate(
                [
                    'user_id' => $userId,
                    'screen_id' => $item['screen_id'],
                    'group_id' => $groupId,
                ],
                [
                    'sequence' => $item['sequence'],
                    'updated_by' => $userId,
                ]
            );
            if ($arrangement->wasRecentlyCreated) {
                $arrangement->created_by = $userId;
                $arrangement->save();
            }
        }
        AssignScreenService::sortScreensInSession($user);

        return response()->json(['success' => true]);
    }
}
