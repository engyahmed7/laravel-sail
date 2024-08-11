<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Notifications\TestNotification;
use Illuminate\Http\Request;

class sendNotification extends Controller
{
    public function sendWebNotification()
   {
     $user = User::find(1); 
     $user->notify(new TestNotification());
   }
}
