<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\RoleUser;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class RoleUserController extends Controller
{
    public function storeId(Request $request): RedirectResponse
    {
        $request->validate([
            'user_id' => 'required',
            'role_id' => 'array',
        ]);
        $currentUser = $request->session()->get('user');

        $userId = $request->input('user_id');
        $roleIds = $request->input('role_id');

        $user = User::find($userId);
        $userName = null;
        $user->roles()->detach();
        if ($user?->parent_id && $user?->guardian) {
            $motherNameWithId = trim(str_replace(' ', '', ($user?->guardian?->mother_name ?? '') . ($user?->guardian?->id ?? '')));
            $fatherNameWithId = trim(str_replace(' ', '', ($user?->guardian?->father_name ?? '') . ($user?->guardian?->id ?? '')));
            $currentUserName = trim(str_replace(' ', '', $user?->user_name ?? ''));
        
            if ($currentUserName === $motherNameWithId) {
                $userName = $user?->guardian?->mother_name; 
            } elseif ($currentUserName === $fatherNameWithId) {
                $userName = $user?->guardian?->father_name; 
            }
        }
        
        if (!$userName && $user?->account) {
            $userName = $user?->account?->name;
        }
        
        $data = [];

        foreach ($roleIds as $roleId) {

            $data[] = [
                'user_id' => $userId,
                'role_id' => $roleId,
                'created_at' => now(),
                'created_by' => $currentUser->id,
                'group_id' => $currentUser->group_id,
            ];
        }

        RoleUser::insert($data);
        $roles = Role::whereIn('id', $roleIds)->get();
        $names = $roles->pluck('name')->toArray();
        $implodeName = implode(', ', $names);

        return redirect()->route('userManagement.index')
            ->with('success', 'Role ' . $implodeName . ' Assigned Successfuly To ' . $userName);
    }
}
