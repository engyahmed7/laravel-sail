<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Kreait\Firebase\Database;

class ChatController extends Controller
{
    protected $database;
    protected $dbname = 'chat';

    public function __construct(Database $database)
    {
        $this->database = $database;
    }

    public function sendMessage(Request $request)
    {
        $newPost = $this->database
            ->getReference($this->dbname)
            ->push([
                'user_id' => $request->user_id,
                'message' => $request->message,
                'email' => $request->email,
                'timestamp' => now()->timestamp,
            ]);

        return response()->json($newPost->getValue());
    }

    public function getMessages()
    {
        $messages = $this->database
            ->getReference($this->dbname)
            ->getValue();

        return response()->json($messages);
    }
}