<?php
namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class TestNotification extends Notification
{
    use Queueable;

    public function via($notifiable)
    {
        return ['fcm'];
    }

    public function toFcm($notifiable)
{
    return [
        'to' => $notifiable->routeNotificationForFcm(),
        'notification' => [
            'title' => 'Your Title',
            'body' => 'Your Body',
        ],
        'data' => [
            'key1' => 'value1',
            'key2' => 'value2',
        ],
    ];
}

}