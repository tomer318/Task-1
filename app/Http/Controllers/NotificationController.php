<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        $notifications = \App\Models\Notification::where('user_id', auth()->id())
            ->latest()
            ->paginate(6); // Phân trang mỗi trang 6 thông báo

        return view('shop.profile.notifications', compact('notifications'));
    }

    public function markAsRead(Request $request, Notification $notification)
    {
        if ($notification->user_id !== $request->user()->id) {
            abort(403);
        }

        $notification->update(['is_read' => true]);

        if ($request->wantsJson()) {
            return response()->json(['success' => true]);
        }

        return redirect($notification->link ?? route('profile.notifications'));
    }

    public function markAllAsRead(Request $request)
    {
        $request->user()->notifications()->where('is_read', false)->update(['is_read' => true]);

        return back()->with('success', 'Đã đánh dấu đọc tất cả thông báo!');
    }
}