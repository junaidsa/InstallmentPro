<?php

namespace App\Services;

use Carbon\Carbon;
use App\Models\GroupSetting;
use Illuminate\Support\Facades\Session;

class SettingService
{
    public function updateSettings(array $settings)
    {
        $groupId = Session::get('user')->group_id;

        foreach ($settings as $name => $value) {
            GroupSetting::updateOrCreate(
                ['group_id' => $groupId, 'name' => $name],
                ['value' => $value]
            );
        }
        session()->put(
            'group_settings',
            array_merge(session('group_settings', []), $settings)
        );
    }

    public  function getSetting($keys = null, $fetchFromDb = false)
    {
        $groupId = auth()->user()?->group_id;
        $settings = session('group_settings', []);

        if ($fetchFromDb || empty($settings)) {
            $settings = GroupSetting::where('group_id', $groupId)
                ->pluck('value', 'name')
                ->toArray();

            session(['group_settings' => $settings]);

            cache()->put("group_settings_{$groupId}", $settings, now()->addHours(6));
        }

        if (is_array($keys)) {
            return array_intersect_key($settings, array_flip($keys));
        }

        if (is_string($keys)) {
            return $settings[$keys] ?? null;
        }

        return $settings;
    }

    public static function formatDateTime($dateTime, $default = '--:--')
    {
        if (!$dateTime) {
            return [
                'date' => $default,
                'time' => $default,
                'full' => $default,
            ];
        }

        $settings = session('group_settings', []);
        $dateFormat = $settings['date_format'] ?? 'd-m-Y';
        $timeFormat = ($settings['time_format'] ?? GroupSetting::HOUR_12) === GroupSetting::HOUR_24
            ? 'H:i'
            : 'h:i A';

        $carbon = Carbon::parse($dateTime);

        return [
            'date' => $carbon->format($dateFormat),
            'time' => $carbon->format($timeFormat),
            'full' => $carbon->format($dateFormat . ' ' . $timeFormat),
        ];
    }


    public static function formatDate($date, $default = '--')
    {
        if (!$date) return $default;

        $settings = session('group_settings', []);
        $dateFormat = $settings['date_format'] ?? 'd-m-Y';
        return Carbon::parse($date)->format($dateFormat);
    }

    public static function formatTime($time, $default = '--:--')
    {
        if (!$time) return $default;

        $settings = session('group_settings', []);
        $timeFormat = ($settings['time_format'] ?? GroupSetting::HOUR_12) === GroupSetting::HOUR_24
            ? 'H:i'
            : 'h:i A';
        return Carbon::parse($time)->format($timeFormat);
    }

    public static function getJsDateFormats(): array
    {
        $settings = session('group_settings', []);
        $dateFormat = $settings['date_format'] ?? 'd-m-Y';

        return [
            'bootstrap' => strtr($dateFormat, GroupSetting::DATE_FORMAT_JS['bootstrap']),
            'flatpickr' => strtr($dateFormat, GroupSetting::DATE_FORMAT_JS['flatpickr']),
            'moment'    => strtr($dateFormat, GroupSetting::DATE_FORMAT_JS['moment']),
        ];
    }
}
