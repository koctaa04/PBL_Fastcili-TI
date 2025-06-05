<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function markAsRead($notificationId)
    {
        $user = Auth::user();
        $notification = $user->notifications()->where('id', $notificationId)->first();

        if ($notification) {
            $notification->markAsRead();
            return response()->json(['success' => true, 'message' => 'Notification marked as read.']);
        }

        return response()->json(['success' => false, 'message' => 'Notification not found.'], 404);
    }

    public function markAllAsRead()
    {
        $user = Auth::user();
        $user->unreadNotifications->markAsRead(); // Marks all unread as read
        return response()->json(['success' => true, 'message' => 'All notifications marked as read.']);
    }

    // You might also want a method to view all notifications
    public function index()
    {
        $notifications = Auth::user()->unreadNotifications; // Paginate for larger lists
        return view('layouts.navbars.navs.auth', compact('notifications'));
    }
}
