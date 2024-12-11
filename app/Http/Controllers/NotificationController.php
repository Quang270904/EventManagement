<?php

namespace App\Http\Controllers;

use App\Http\Requests\NotificationRequest;
use App\Models\Notification;
use GuzzleHttp\Psr7\Request;

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
            'notifications' => $notifications,
        ]);
    }

    public function updateStatus(NotificationRequest $request)
    {
        $notification = Notification::find($request->notification_id);

        if ($notification) {
            $notification->status = 'read';
            $notification->save();

            return response()->json([
                'success' => true,
                'message' => 'Notification marked as read'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Notification not found'
        ]);
    }
}
