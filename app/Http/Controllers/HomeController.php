<?php

namespace App\Http\Controllers;

use App\Models\Manufacturer;
use App\Models\Vehicle;

class HomeController extends Controller
{
    public function index()
    {
        return view('home', ['vehicleCount' => Vehicle::active()->count(), 'manufacturerCount' => Manufacturer::where('status', true)->count(), 'latestVehicles' => Vehicle::active()->with('manufacturer')->latest()->take(6)->get()]);
    }

    public function about()
    {
        return view('about');
    }
}
