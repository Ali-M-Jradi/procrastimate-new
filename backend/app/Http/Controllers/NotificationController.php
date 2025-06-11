<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function recieveNotifications()
    {
        $notifications = auth()->user()->notifications;
        return view('notification.index', compact('notifications'));
    }

    public function showNotificationForm()
    {
        return view('notification.create');
    }

    public function sendNotification(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'message' => 'required|string|max:1000',
            'to_user_id' => 'required|exists:users,id',
        ]);
        auth()->user()->notifications()->create([
            'title' => $request->title,
            'message' => $request->message,
            'to_user_id' => $request->to_user_id,
        ]);
        return redirect()->route('notifications.view')->with('success', 'Notification created successfully!');
    }
}
