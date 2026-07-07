<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ComparisonHistory;
use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        return view('admin.dashboard', [
            'userCount' => User::count(), 'vehicleCount' => Vehicle::count(), 'compareCount' => ComparisonHistory::count(),
            'latestVehicles' => Vehicle::with('manufacturer')->latest()->take(5)->get(),
            'typeStats' => Vehicle::select('vehicle_type', DB::raw('count(*) as total'))->groupBy('vehicle_type')->pluck('total', 'vehicle_type'),
            'fuelStats' => Vehicle::select('fuel_type', DB::raw('count(*) as total'))->groupBy('fuel_type')->pluck('total', 'fuel_type'),
        ]);
    }
}
