<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * Fetch unread notifications for the authenticated user.
     */
    public function index()
    {
        return response()->json(Auth::user()->unreadNotifications);
    }

    /**
     * Mark a specific notification as read.
     */
    public function markAsRead(Request $request, $notificationId)
    {
        $notification = Auth::user()->notifications()->findOrFail($notificationId);
        $notification->markAsRead();
        
        return response()->json(['status' => 'success']);
    }
}

