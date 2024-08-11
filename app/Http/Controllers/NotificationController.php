<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\FirebaseService;

class NotificationController extends Controller
{
    protected $firebaseService;

    public function __construct(FirebaseService $firebaseService)
    {
        $this->firebaseService = $firebaseService;
    }

    public function saveToken(Request $request)
    {
        $token = $request->input('token');
        // Save the token to the database or any other storage
        
        return response()->json(['status' => 'success']);
    }
    public function send(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'body' => 'required',
            'token' => 'required',
        ]);

        $token = $request->input('token');
        $title = $request->input('title');
        $body = $request->input('body');

        try {
            $this->firebaseService->sendNotification($token, $title, $body);
            return response()->json(['message' => 'Notification sent successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to send notification', 'error' => $e->getMessage()], 500);
        }
    }
}
