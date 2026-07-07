<?php

namespace App\Http\Controllers;

use App\Models\Notification;

class NotificationController extends Controller
{
    public function index()
    {
        return view('notifications.index', ['notifications' => Notification::where('status', true)->latest()->paginate(12)]);
    }

    public function read(Notification $notification)
    {
        request()->user()->notifications()->syncWithoutDetaching([$notification->id => ['read_at' => now()]]);

        return back();
    }
}
