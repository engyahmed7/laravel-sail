<?php

namespace App\Broadcasting;

use App\Notifications\TestNotification;
use Illuminate\Notifications\Notification;
use GuzzleHttp\Client;
use Google_Client;
use Illuminate\Support\Facades\Log;

class FcmChannel
{
    public function send($notifiable, TestNotification $notification)
    {
        $message = $notification->toFcm($notifiable);
        Log::info('FCM Token: ' . $message['to']);
        Log::info('Notification Payload: ', $message);

        $client = new Client();

        $response = $client->post('https://fcm.googleapis.com/v1/projects/laravel-blog-fdc7a/messages:send', [
            'headers' => [
                'Authorization' => 'Bearer ' . $this->getAccessToken(),
                'Content-Type' => 'application/json',
            ],
            'json' => [
                'message' => [
                    'token' => $message['to'], // Ensure this is a string token
                    'notification' => [
                        'title' => $message['notification']['title'], // Ensure these are properly structured
                        'body' => $message['notification']['body'],
                    ],
                    'data' => $message['data'], // Ensure this is an associative array
                ],
            ],
        ]);

        return $response;
    }


    private function getAccessToken()
    {
        $credentialsPath = base_path('config/firebase-credentials.json');
        $client = new Google_Client();
        $client->setAuthConfig($credentialsPath);
        $client->addScope('https://www.googleapis.com/auth/firebase.messaging');

        $token = $client->fetchAccessTokenWithAssertion();
        return $token['access_token'];
    }
}
