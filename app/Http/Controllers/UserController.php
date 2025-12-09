<?php

namespace App\Http\Controllers;

use App\Models\Account;
use Illuminate\Support\Facades\Mail;
use App\Models\Role;
use App\Models\User;
use App\Services\NotificationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\View\View;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $user = Session::get('user');
        $groupId = Session::get('user')->group_id;
        $users = User::with('account')->where('group_id', $groupId)->whereNull('deleted_at')->get();
        $roles = Role::all();
        $accounts = Account::where(['group_id' => $groupId, 'type' => __('lang.EMPLOYEE')])->get();

        return view('userManagement.index', compact('users', 'roles', 'accounts', 'user'))
            ->with('i');
    }
    public function store(Request $request): RedirectResponse
    {
        $groupId = Session::get('user')->group_id;
        $request->validate(
            [
                'employee_id' => 'required',
                'user_name' => 'required',
                'password' => 'required',
                'email' => 'required',
            ]
        );
        $user = new User([
            'employee_id' => $request->input('employee_id'),
            'group_id' => $groupId,
            'user_name' => $request->input('user_name'),
            'email' => $request->input('email'),
            'password' => $request->input('password'),
        ]);
        $user->save();
        $name = $user->account->name ?? $user->user_name;
        $username = $user->user_name;
        $signInUrl = url('/login');
        $nurseryName = Session::get('user')->groups->first()->name ?? 'Our Nursery';
        $emailData = [
            'name' => $name,
            'username' => $username,
            'password' => $request->input('password'),
            'nurseryName' => $nurseryName,
            'signInUrl' => $signInUrl,
        ];
        try {
            Mail::send('email.newCreatedUse', $emailData, function ($message) use ($user, $nurseryName) {
                $message->to($user->email)
                    ->subject('Welcome to ' . $nurseryName);
            });
        } catch (\Exception $e) {
            Log::error('Failed to send user creation email to ' . $user->email . ': ' . $e->getMessage());
        }

        return redirect()->route('userManagement.index')
            ->with('success', 'User ' . $user->account->name . ' Created successfully.');
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

            if ($name === 'user_name') {
                $existingUsername = User::where('group_id', $groupId)->where('user_name', $value)->exists();

                if ($existingUsername) {
                    return response()->json(['status' => 'error', 'message' => $formattedName . ' ' . $value . ' ' . 'already exists.'], 403);
                }
            }

            User::find($request->pk)->update([
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
        $user = User::find($id);

        if (!$user) {
            return redirect()->route('userManagement.index')
                ->with('error', 'User not found.');
        }

        $user->delete();

        return redirect()->route('userManagement.index')
            ->with('success', 'User ' . $user->name . ' Deleted successfully.');
    }

    public function validUsername(Request $request)
    {
        $groupId = Session::get('user')->group_id;
        $username = $request->input('user_name');
        $exists = User::where('group_id', $groupId)
            ->whereRaw('LOWER(user_name) = ?', [strtolower($username)])
            ->exists();

        return response()->json(['exists' => $exists]);
    }

    public function changePassword(Request $request, $userId)
    {
        $request->validate([
            'password' => 'required',
            'confirm_password' => 'required',
        ]);
        $user = User::where('id', $userId)->update([
            'password' => Hash::make($request->password),
        ]);

        if ($user) {
            return response()->json(['success' => true]);
        } else {
            return response()->json(['error' => true]);
        }
    }

    public function validateCurrentPassword(Request $request)
    {
        $currentPassword = $request->input('password');

        if (!Hash::check($currentPassword, Session::get('user')->password)) {
            return response()->json(['valid' => false]);
        }

        return response()->json(['valid' => true]);
    }

    public function profile()
    {
        $user = Session::get('user');
        return view('profile', compact('user'));
    }
    public function updateProfile(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('users')
                    ->ignore(auth()->id())
                    ->where(function ($query) {
                        return $query->where('group_id', auth()->user()->group_id);
                    }),
                'regex:/^[a-zA-Z0-9_-]+$/',
            ],
            'phone_number' => 'nullable|string|max:20',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        /**
         * @var \App\Models\User $user
         */
        $user = auth()->user();
        $account = Account::find($user->employee_id);
        $user->user_name = $request->user_name;
        $user->save();
        if ($account) {
            $account->contact_person = $request->contact_person;
            $account->address = $request->address;
            $account->save();
        }
        return back()->with('success', 'Profile updated successfully!');
    }


    public function uploadImage(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'profile_image' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()->all(),
            ], 422);
        }
        $user = auth()->user();
        $folderName = config('imagepathconstant.PROFILE_PATH');
        $image = $request->file('profile_image');
        $imageName = time() . '.' . $image->getClientOriginalExtension();
        $image->move(public_path($folderName), $imageName);
        /**
         * @var \App\Models\User $user
         */
        $user->profile_image = $folderName . $imageName;
        $user->save();
        return response()->json(['success' => __('lang.PROFILE_IMAGE_UPDATED')], 200);
    }

    public function notifications()
    {
        $userId = Auth::id();
        $notificationService = new NotificationService();
        $notifications = $notificationService->getData(limit: 5, paginate: false, onlyUnread: true);
        $count = $notificationService->countUnread();

        return response()->json([
            'count' => $count,
            'notifications' => $notifications
        ]);
    }
    public function markNotificationAsRead(Request $request)
    {
        $notificationId = $request->input('notification_id');
        if (!$notificationId) {
            return response()->json(['success' => false, 'message' => 'Invalid data.'], 400);
        }
        $notificationService = new NotificationService();
        $success = $notificationService->markAsRead($notificationId);

        return response()->json(['success' => $success]);
    }
    public function markAllAsRead()
    {
        $notificationService = new NotificationService();
        $notificationService->markAllAsRead();

        return response()->json(['success' => true]);
    }
}
