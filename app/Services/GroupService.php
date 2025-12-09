<?php

namespace App\Services;

use App\Models\Account;
use App\Models\Group;
use App\Models\Role;
use App\Models\RoleUser;
use App\Models\User;
use Config;
use Illuminate\Support\Carbon;

class GroupService
{
    const ACTIVE_STATUS = 1;

    const IN_ACTIVE_STATUS = 0;

    const ADMIN = 'admin';

    const LIFE_TIME = 'Life Time';

    public function store(array $validatedData)
    {
        $currentDate = Carbon::now();

        $billingCycle = $validatedData['billing_cycle'];
        if ($billingCycle === self::LIFE_TIME) {
            $paidTill = null;
        } else {
            $paidTill = $currentDate->addDays($billingCycle);
        }

        $expiryDate = $paidTill ? $paidTill->copy()->addDays(10) : null;

        $nextPayment = $paidTill ? $paidTill->copy()->addDay() : null;

        $logoPath = null;
        if (isset($validatedData['logo'])) {
            $logoPath = $validatedData['logo']->store('images', 'public');
        }

        if (isset($validatedData['logo'])) {
            $logoPath = 'images/'.time().'.'.$validatedData['logo']->getClientOriginalExtension();
            $validatedData['logo']->move(public_path('groupImages'), $logoPath);
        }

        Group::create([
            'name' => $validatedData['name'],
            'city' => $validatedData['city'],
            'address' => $validatedData['address'],
            'contact' => $validatedData['contact'],
            'fee' => $validatedData['fee'],
            'billing_cycle' => $billingCycle,
            'paid_till' => $paidTill,
            'trial_expiry' => $expiryDate,
            'next_payments' => $nextPayment,
            'logo' => $logoPath,
            'status' => self::ACTIVE_STATUS,
            'company_type' => $validatedData['company_type'],
        ]);
    }

    public function assign($validatedData)
    {
        $account = new Account([
            'group_id' => $validatedData['group_id'],
            'account_type' => Config::get('accountTypeConstants.accountTypes.ACCOUNT_TYPE_EMPLOYEE'),
            'designation' => Config::get('designationConstants.designations.ADMIN'),
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
        ]);
        $account->save();

        $user = new User([
            'group_id' => $validatedData['group_id'],
            'employee_id' => $account->id,
            'user_name' => $validatedData['user_name'],
            'email' => $validatedData['email'],
            'password' => $validatedData['password'],
        ]);
        $user->save();

        $roleId = Role::where('name', self::ADMIN)->pluck('id')->first();
        $roleUser = new RoleUser([
            'group_id' => $validatedData['group_id'],
            'user_id' => $user->id,
            'role_id' => $roleId,
        ]);
        $roleUser->save();
    }

    public function update($groupId, $name, $value)
    {
        $group = Group::find($groupId);

        $group->update([
            $name => $value,
        ]);

        $formatName = ucwords(str_replace('_', ' ', $name));

        return ['name' => $formatName, 'value' => $value];
    }

    public function inActive($groupId)
    {
        $group = Group::find($groupId);

        $group->status = $group->status == self::ACTIVE_STATUS ? self::IN_ACTIVE_STATUS : self::ACTIVE_STATUS;
        $group->save();

        $name = $group->name;
        $status = $group->status == self::ACTIVE_STATUS ? 'Active' : 'In-Active';

        return ['name' => $name, 'status' => $status];
    }

    public function updateCycle($groupId)
    {
        $group = Group::find($groupId);

        $billingCycle = $group->billing_cycle;
        $paidTill = Carbon::parse($group->paid_till);

        $newPaidTill = $paidTill->addDays($billingCycle);

        $trialExpiry = $newPaidTill->copy()->addDays(10);
        $nextPayment = $newPaidTill->copy()->addDay();

        $group->update([
            'paid_till' => $newPaidTill,
            'trial_expiry' => $trialExpiry,
            'next_payments' => $nextPayment,
        ]);
    }
}
