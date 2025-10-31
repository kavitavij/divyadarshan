<?php

namespace App\Http\Controllers\HotelManager;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Notifications\DatabaseNotification as Notification;

class NotificationController extends Controller
{
    /**
     * Fetch unread notifications for the dropdown.
     */
    public function fetchUnread()
    {
        return response()->json(Auth::user()->unreadNotifications);
    }

    /**
     * Show the dedicated page with all notifications.
     */
    public function showAll(Request $request)
    {
        $query = Auth::user()->notifications();

        // Apply date filter if provided
        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->input('date'));
        }

        // Get paginated results with query string preserved
        $notifications = $query->latest()->paginate(15)->withQueryString();

        return view('hotel-manager.notifications.index', compact('notifications'));
    }


    /**
     * Mark a specific notification as read.
     */
    public function markAsRead(Notification $notification)
    {
        // Add a policy check here if needed to ensure the user owns the notification
        $notification->markAsRead();
        
        return response()->json(['status' => 'success']);
    }
}