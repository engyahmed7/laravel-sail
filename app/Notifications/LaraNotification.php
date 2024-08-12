<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Kutia\Larafirebase\Messages\FirebaseMessage;

class LaraNotification extends Notification
{
    use Queueable;

    public function via($notifiable)
    {
        return ['firebase'];
    }
    
    public function toFirebase($notifiable)
    {
        $deviceTokens = [
            '{TOKEN_1}',
        ];
        
        return (new FirebaseMessage)
            ->withTitle('Hey, '. $notifiable->first_name)
            ->withBody('Happy Birthday!')
            ->asNotification($deviceTokens); 
    }
     
}