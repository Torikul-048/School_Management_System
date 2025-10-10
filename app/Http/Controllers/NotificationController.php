<?php

namespace App\Http\Controllers;

use App\Models\NotificationSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = Auth::user()->notifications()->paginate(20);
        $unreadCount = Auth::user()->unreadNotifications->count();

        return view('notifications.index', compact('notifications', 'unreadCount'));
    }

    public function markAsRead($id)
    {
        $notification = Auth::user()->notifications()->findOrFail($id);
        $notification->markAsRead();

        return redirect()->back()->with('success', 'Notification marked as read!');
    }

    public function markAllAsRead()
    {
        Auth::user()->unreadNotifications->markAsRead();

        return redirect()->back()->with('success', 'All notifications marked as read!');
    }

    public function destroy($id)
    {
        $notification = Auth::user()->notifications()->findOrFail($id);
        $notification->delete();

        return redirect()->back()->with('success', 'Notification deleted!');
    }

    public function settings()
    {
        $settings = NotificationSetting::getSettings(Auth::id());

        return view('notifications.settings', compact('settings'));
    }

    public function updateSettings(Request $request)
    {
        $validated = $request->validate([
            'email_enabled' => 'boolean',
            'sms_enabled' => 'boolean',
            'push_enabled' => 'boolean',
            'notify_announcements' => 'boolean',
            'notify_events' => 'boolean',
            'notify_notices' => 'boolean',
            'notify_messages' => 'boolean',
            'notify_fees' => 'boolean',
            'notify_attendance' => 'boolean',
            'notify_grades' => 'boolean',
            'notify_assignments' => 'boolean',
        ]);

        $settings = NotificationSetting::getSettings(Auth::id());
        $settings->update($validated);

        return redirect()->back()->with('success', 'Notification settings updated successfully!');
    }

    public function unread()
    {
        $notifications = Auth::user()->unreadNotifications;

        return response()->json([
            'count' => $notifications->count(),
            'notifications' => $notifications->take(10),
        ]);
    }
}
