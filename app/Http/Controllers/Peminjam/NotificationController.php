<?php

namespace App\Http\Controllers\Peminjam;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = Notification::with('notifiable')
            ->where('id_user', Auth::id())
            ->latest()
            ->paginate(10);

        return view('peminjam.notifikasi.index', compact('notifications'));
    }

    public function markAsRead($id)
    {
        $notification = Notification::where('id', $id)
            ->where('id_user', Auth::id())
            ->firstOrFail();

        if (!$notification->dibaca) {
            $notification->update([
                'dibaca' => true
            ]);
        }

        return back();
    }
}