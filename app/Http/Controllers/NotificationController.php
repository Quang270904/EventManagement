<?php

namespace App\Http\Controllers;

use App\Models\Notification;

class NotificationController extends Controller
{
    public function formNotification()
    {
        $notifications = Notification::latest()->get();

        return view('event_managers.dashboard.components.navbar', compact('notifications'));
    }

    public function getAllNotification()
    {
        $notifications = Notification::where('status', 'unread')->latest()->get();

        return response()->json([
            'notifications' => $notifications
        ]);
    }
}
