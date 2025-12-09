<?php

namespace App\Services;

use App\Models\RoleScreen;
use App\Models\ScreenTab;
use App\Models\SoftwareScreen;
use App\Models\UsersScreenArrangement;
use Illuminate\Support\Facades\Session;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class AssignScreenService
{
    public static function assignScreens($user)
    {
        $groupId = $user->group_id;
        $roleIds = $user->roles->pluck('id')->toArray();

        $roles = Role::find($roleIds);

        $roleScreenIds = RoleScreen::whereIn('role_id', $roleIds)
            ->pluck('screen_id')
            ->toArray();

        $user->load('userScreenTabs.screenTab');

        $isAdmin = $user->roles->contains(function ($role) {
            return strtolower($role->name) === 'admin';
        });

        if ($isAdmin) {
            $allScreenIds = SoftwareScreen::pluck('id')->toArray();
            $screenTabs = ScreenTab::pluck('name')->unique()->toArray();
        } else {
            $userScreenIds = $user->userScreenTabs
                ->pluck('screenTab.screen_id')
                ->filter()
                ->unique()
                ->toArray();

            $allScreenIds = array_unique(array_merge($roleScreenIds, $userScreenIds));
            $screenTabs = $user->userScreenTabs
                ->pluck('screenTab.name')
                ->filter()
                ->unique()
                ->toArray();
        }

        $allowedScreens = SoftwareScreen::whereIn('id', $allScreenIds)->get();

        $permissions = collect();
        foreach ($allowedScreens as $screen) {
            $permissionName = 'Can manage ' . $screen->permission_key;

            $permission = Permission::firstOrCreate(
                ['name' => $permissionName],
                ['group_id' => $groupId]
            );

            $permissions->push($permission);
        }

        $screensArr = self::prepareScreens($allowedScreens);
        $user->parentScreens = $screensArr['parentScreens'];
        $user->childScreens = $screensArr['childScreens'];
        $user->permissions = $permissions;
        $user->screenTabNames = $screenTabs;

        if (!$user->hasAnyRole($roles)) {
            $user->assignRole($roles);
        }

        Session::put('user', $user);
        self::sortScreensInSession($user);
    }

    private static function prepareScreens($allowedScreens)
    {
        $children = [];
        $parents = [];

        foreach ($allowedScreens as $allowedScreen) {
            if ($allowedScreen->is_parent == 1) {
                $parents[$allowedScreen->id] = $allowedScreen;
            } else {
                $children[$allowedScreen->parent_id][] = $allowedScreen;
            }
        }

        return [
            'parentScreens' => $parents,
            'childScreens' => $children,
        ];
    }

    public static function sortScreensInSession($user)
    {
        $arrangedIds = UsersScreenArrangement::where('user_id', $user->id)
            ->orderBy('sequence')
            ->pluck('screen_id')
            ->toArray();

        if (empty($arrangedIds)) {
            return;
        }

        $originalParents = collect($user->parentScreens);
        $orderMap = array_flip($arrangedIds);
        $maxOrder = count($orderMap);
        $sortedParents = $originalParents->sortBy(function ($screen, $id) use ($orderMap, $maxOrder) {
            return $orderMap[$id] ?? $maxOrder + $id;
        });

        $user->parentScreens = $sortedParents;
        Session::put('user', $user);
    }
}
