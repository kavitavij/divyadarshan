<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Notifications\AdminAnnouncement;
use Illuminate\Support\Facades\Notification;

class AnnouncementController extends Controller
{
    // Show the form to write the announcement
    public function create()
    {
        return view('admin.announcements.create');
    }

    // Send the announcement to all users
    public function store(Request $request)
    {
        // 1. Validate the message and the new target_role field
        $validated = $request->validate([
            'message' => 'required|string|min:10',
            'target_role' => 'required|in:user,temple_manager,hotel_manager',
        ]);

        $role = $validated['target_role'];
        
        // 2. Build the query to find the correct group of users
        $users = User::where('role', $role)->get();

        if ($users->isEmpty()) {
            return redirect()->back()->with('error', "No users found with the role '{$role}'.");
        }

        // 3. Send the notification to the selected group
        Notification::send($users, new AdminAnnouncement($validated['message']));
        
        // 4. Create a dynamic success message
        $roleName = str_replace('_', ' ', $role); // Converts 'temple_manager' to 'temple manager'
        $successMessage = "Announcement has been sent to all " . ucwords($roleName) . "s!";

        return redirect()->route('admin.announcements.create')
                        ->with('success', $successMessage);
    }
}