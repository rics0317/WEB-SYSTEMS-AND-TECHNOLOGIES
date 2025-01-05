<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = Notification::where('user_id', Auth::id())->orderBy('created_at', 'desc')->get();
        return response()->json($notifications);
    }

    public function markAsRead($id)
    {
        $notification = Notification::find($id);
        if ($notification && $notification->user_id == Auth::id()) {
            $notification->read = true;
            $notification->save();
            return redirect()->back()->with('success', 'Notification marked as read.');
        }
        return redirect()->back()->with('error', 'Unable to mark notification as read.');
    }

    public function markAllAsRead()
    {
        Notification::where('user_id', Auth::id())->update(['read' => true]);
        return redirect()->back()->with('success', 'All notifications marked as read.');
    }

    public function delete($id)
    {
        $notification = Notification::find($id);
        if ($notification && $notification->user_id == Auth::id()) {
            $notification->delete();
            return redirect()->back()->with('success', 'Notification deleted.');
        }
        return redirect()->back()->with('error', 'Unable to delete notification.');
    }

    public function deleteSelected(Request $request)
    {
        $notificationIds = $request->input('notifications');
        if ($notificationIds) {
            Notification::where('user_id', Auth::id())->whereIn('id', explode(',', $notificationIds))->delete();
            return redirect()->back()->with('success', 'Selected notifications deleted.');
        }
        return redirect()->back()->with('error', 'No notifications selected.');
    }

    public function viewAll()
    {
        // Fetch all notifications for the authenticated user
        $notifications = Notification::where('user_id', Auth::id())->orderBy('created_at', 'desc')->get();
        return view('users.profile.notification', compact('notifications'));
    }
}
