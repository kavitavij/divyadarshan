<?php

namespace App\Http\Controllers\TempleManager;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index()
    {
        return response()->json(Auth::user()->unreadNotifications);
    }

    public function showAll(Request $request)
    {
        $query = Auth::user()->notifications();

        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->input('date'));
        }

        $notifications = $query->latest()->paginate(15)->withQueryString();

        return view('temple-manager.notifications.index', compact('notifications'));
    }

    public function markAsRead(Request $request, $notificationId)
    {
        $notification = Auth::user()->notifications()->findOrFail($notificationId);
        $notification->markAsRead();

        return response()->json(['status' => 'success']);
    }
    public function fetchUnread()
{
    return response()->json(Auth::user()->unreadNotifications);
}
}