<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Notification;
use App\Models\User;

class AdminNotificationController extends Controller
{
    // View all notifications
    public function index()
    {
        $notifications = Notification::orderByDesc('created_at')->get();
        return response()->json($notifications);
    }

    // Send notification to a user
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'title' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        $notification = Notification::create($validated);

        return response()->json([
            'message' => 'Notification sent successfully.',
            'notification' => $notification
        ], 201);
    }

    // Mark a notification as read
    public function markRead($id)
    {
        $notification = Notification::findOrFail($id);
        $notification->update(['read_at' => now()]);

        return response()->json([
            'message' => 'Notification marked as read.'
        ]);
    }
}
