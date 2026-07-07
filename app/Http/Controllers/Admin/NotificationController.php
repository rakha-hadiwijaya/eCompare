<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index()
    {
        return view('admin.notifications.index', ['notifications' => Notification::latest()->paginate(20)]);
    }

    public function create()
    {
        return view('admin.notifications.form', ['notification' => new Notification]);
    }

    public function store(Request $request)
    {
        Notification::create($this->validated($request));

        return redirect()->route('admin.notifications.index')->with('success', 'Notifikasi diterbitkan.');
    }

    public function edit(Notification $notification)
    {
        return view('admin.notifications.form', compact('notification'));
    }

    public function update(Request $request, Notification $notification)
    {
        $notification->update($this->validated($request));

        return redirect()->route('admin.notifications.index')->with('success', 'Notifikasi diperbarui.');
    }

    public function destroy(Notification $notification)
    {
        $notification->delete();

        return back()->with('success', 'Notifikasi dihapus.');
    }

    private function validated(Request $request)
    {
        return $request->validate(['title' => ['required', 'max:150'], 'content' => ['required', 'max:3000'], 'status' => ['required', 'boolean']]);
    }
}
