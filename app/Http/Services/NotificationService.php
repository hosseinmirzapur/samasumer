<?php


namespace App\Http\Services;

/* Notification Schema:
 * title
 * message
 * tx_id (sometimes)
 */

use App\Http\Resources\NotificationResource;
use JetBrains\PhpStorm\ArrayShape;

class NotificationService
{
    #[ArrayShape(['notifs' => "\Illuminate\Http\Resources\Json\AnonymousResourceCollection"])]
    public function userNotifs(int $count = null): array
    {
        $notifs = !exists($count) ? currentUser()->notifications : currentUser()->notifications->take($count);
        return [
            'notifs' => NotificationResource::collection($notifs)
        ];
    }

    public function markAllAsRead()
    {
        currentUser()->unreadNotifications()->update([
            'read_at' => now()
        ]);
    }
}
