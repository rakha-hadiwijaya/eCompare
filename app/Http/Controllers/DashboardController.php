<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\Vehicle;

class DashboardController extends Controller
{
    public function index()
    {
        $user = request()->user();

        return view('dashboard', [
            'historyCount' => $user->comparisons()->count(), 'favoriteCount' => $user->favorites()->count(),
            'histories' => $user->comparisons()->with('vehicles.manufacturer')->latest()->take(5)->get(),
            'notifications' => Notification::where('status', true)->latest()->take(5)->get(),
            'recommended' => Vehicle::active()->with('manufacturer')->when($user->preference?->favorite_vehicle_type, fn ($q, $v) => $q->where('vehicle_type', $v))->orderByDesc('created_at')->take(4)->get(),
        ]);
    }
}
