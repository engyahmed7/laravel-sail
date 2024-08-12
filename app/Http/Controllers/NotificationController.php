<?php

namespace App\Http\Controllers;

use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;

class NotificationController extends Controller
{
    private $deviceTokens = [
        'dtB-YaZ9Remzvk0pUSdDVp:APA91bHFsP1-eW-NjnRnUkbbdPTg2JdfKBMD4MHZnTrgWAKhSNmLYAb2mHagMV-dppbF2mTE7qGm0uenhd_87GuxRL9__c7CFAJAN7m5KQ0QeRKIyj--Foi_IVC4rJxrG5O3hs2eb5l3'
    ];

    public function sendNotification()
    {
        $factory = (new Factory)
            ->withServiceAccount(base_path('config/firebase-credentials.json'))
            ->withProjectId('app-experts-8a668');

        $messaging = $factory->createMessaging();

        $token = $this->deviceTokens[0];

        $notification = Notification::create('Title', 'This is a notification body');
        $message = CloudMessage::withTarget('token', $token)->withNotification($notification);

        try {
            $messaging->send($message);
            return response()->json(['message' => 'Notification sent successfully']);
        } catch (\Kreait\Firebase\Exception\Messaging\MessagingError $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }
}
