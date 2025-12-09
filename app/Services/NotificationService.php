<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\NotificationReceiver;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;

class NotificationService
{
    protected $userId;
    protected $groupId;

    public function __construct()
    {
        $user = Auth::user();
        if ($user) {
            $this->userId  = $user->id;
            $this->groupId = $user->group_id;
        }
    }
    public function store(string $title, string $message, string $url, array $viewUserIds, $module = null, array $params = [], $groupId)
    {
        $notification = Notification::create([
            'group_id' => $groupId,
            'title' => $title,
            'message' => $message,
            'url' => $url,
            'module' => $module,
            'params' => json_encode($params),

        ]);
        foreach ($viewUserIds as $receiver_id) {
            NotificationReceiver::create([
                'group_id' => $groupId,
                'notification_id' => $notification->id,
                'user_id' => $receiver_id,
            ]);
        }
        return $notification;
    }

    public function getData(int $limit = 5, bool $paginate = false, bool $onlyUnread = false, $module = Notification::ALL)
    {
        $query = NotificationReceiver::with(['notification'])
            ->where('user_id', $this->userId)
            ->where('group_id', $this->groupId);


        if ($module !== Notification::ALL) {
            if ($module === Notification::BOOKING || $module === Notification::PURCHASE) {
                $query->whereHas('notification', function ($q) use ($module) {
                    $q->where('module', $module);
                });
            } else {
                $query->whereHas('notification', function ($q) {
                    $q->whereNotIn('module', [Notification::BOOKING, Notification::PURCHASE]);
                });
            }
        }
        if ($onlyUnread) {
            $query->where('is_read', 0);
        }

        $query->orderBy('created_at', 'desc');

        $results = $paginate ? $query->paginate($limit) : $query->limit($limit)->get();

        $formatted = [];

        foreach ($results as $item) {
            $notification = $item->notification;
            $userLang = $item->user->lang ?? auth()->user()->lang ?? 'en';
            App::setLocale($userLang);

            $formatted[] = [
                'id' => $notification->id,
                'title' => __($notification->title, json_decode($notification->params, true) ?? []),
                'message' => __($notification->message, json_decode($notification->params, true) ?? []),
                'params' => $notification->params,
                'created_at' => $notification->created_at,
                'created_at_formatted' => \Carbon\Carbon::parse($notification->created_at)->format('g:i A'),
                'url' => $notification->url,
                'is_read' => $item->is_read,
            ];
        }

        return $paginate
            ? [
                'data' => $formatted,
                'pagination' => $results->toArray(),
            ]
            : $formatted;
    }



    public function countUnread(): int
    {
        return NotificationReceiver::where('user_id', $this->userId)->where('group_id', $this->groupId)
            ->where('is_read', false)
            ->count();
    }
    public function markAsRead(int $notificationId): bool
    {
        return NotificationReceiver::where('notification_id', $notificationId)
            ->where('user_id', $this->userId)->where('group_id', $this->groupId)
            ->update(['is_read' => 1]);
    }

    public function markAllAsRead(): bool
    {
        return NotificationReceiver::where('user_id', $this->userId)->where('group_id', $this->groupId)
            ->where('is_read', 0)
            ->update(['is_read' => 1]);
    }
}
