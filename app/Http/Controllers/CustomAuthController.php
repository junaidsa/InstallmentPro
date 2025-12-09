<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\User;
use Illuminate\Support\Facades\App;
use App\Services\AssignScreenService;
use App\Services\SettingService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class CustomAuthController extends Controller
{
    public function index(Request $request)
    {
        if (Auth::check()) {
            return redirect()->intended('dashboard')
                ->with('success', 'Welcome Back ' . $request->user()?->account?->name);
        }

        $userId = $request->cookie('user_id');
        $groupId = $request->cookie('group_id');
        $userName = $request->cookie('user_name');

        if (!empty($userId) && !empty($groupId) && !empty($userName)) {
            $user = User::where('id', $userId)
                ->where('group_id', $groupId)
                ->where('user_name', $userName)
                ->first();

            if ($user) {
                Auth::login($user);

                $locale = $user->lang ?? 'en';
                Session::put('locale', $locale);
                App::setLocale($locale);

                $assignScreenService = new AssignScreenService();
                $assignScreenService->assignScreens($user);

                $settingService = new SettingService();
                $settingService->getSetting(['date_format', 'time_format'], true);

                return redirect('dashboard')
                    ->with('success', 'Welcome Back ' . $user?->account?->name);
            }
        }

        return view('auth.login');
    }

    public function customLogin(Request $request)
    {

        if ($request->ajax()) {
            try {
                $userId = $request->input('user_id');
                $user = Auth::loginUsingId($userId);

                $assignScreenService = new AssignScreenService();
                $assignScreenService->assignScreens($user);

                $settingService = new SettingService();
                $settingService->getSetting(['date_format', 'time_format'], true);

                return response()->json(['success' => true]);
            } catch (\Exception $e) {
                Log::error('Error: ' . $e->getMessage());
                return response()->json(['error' => 'Cannot process your request at this moment.']);
            }
        }

        $request->validate([
            'user_name' => 'required',
            'password' => 'required',
        ]);

        $credentials = $request->only('user_name', 'password');
        $remember = $request->has('remember');

        if (Auth::guard('super_admin')->attempt($credentials)) {
            $superAdmin = Auth::guard('super_admin')->user();
            $superAdmin->role = Group::SUPER_ADMIN;
            Session::put('user', $superAdmin);

            return redirect()->intended('groupManagement')
                ->with('success', 'Super Admin Login Successfully');
        }

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $group = $user->groups;

            if ($group->trial_expiry && $group->trial_expiry <= now() || $group->status == 0) {
                Auth::logout();
                return redirect('login')->with('error', 'Your trial has expired. Please contact support team.');
            }

            if ($group->next_payments && $group->next_payments <= now()) {
                Session::put('next_payment_due', true);
            }

            $locale = $user->lang ?? 'en';
            Session::put('locale', $locale);
            App::setLocale($locale);

            if ($remember) {
                Cookie::queue('user_id', $user->id, Config::get('miscConstant.COOKIE_LIFE'));
                Cookie::queue('group_id', $user->group_id, Config::get('miscConstant.COOKIE_LIFE'));
                Cookie::queue('user_name', $user->user_name, Config::get('miscConstant.COOKIE_LIFE'));
            }

            $assignScreenService = new AssignScreenService();
            $assignScreenService->assignScreens($user);

            $settingService = new SettingService();
            $settingService->getSetting(['date_format', 'time_format'], true);

            return redirect()->intended('dashboard')
                ->with('success', $user?->account?->name . ' Login Successfully');
        }

        return redirect('login')->with('error', 'Invalid username or password');
    }


    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        Cookie::queue(Cookie::forget('user_id'));
        Cookie::queue(Cookie::forget('group_id'));
        Cookie::queue(Cookie::forget('user_name'));
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('login')->with('success', 'Please login to continue');
    }
}
