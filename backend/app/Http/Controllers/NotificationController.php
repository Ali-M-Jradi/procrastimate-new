<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    // Only keep notification viewing method
    public function recieveNotifications()
    {
        $notifications = auth()->user()->notifications;
        return view('notification.index', compact('notifications'));
    }
}
