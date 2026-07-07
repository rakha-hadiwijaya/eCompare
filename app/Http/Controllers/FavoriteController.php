<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;

class FavoriteController extends Controller
{
    public function index()
    {
        return view('favorites.index', ['vehicles' => request()->user()->favorites()->with('manufacturer')->paginate(12)]);
    }

    public function toggle(Vehicle $vehicle)
    {
        $result = request()->user()->favorites()->toggle($vehicle->id);
        $added = count($result['attached']) > 0;

        return back()->with('success', $added ? 'Ditambahkan ke favorit.' : 'Dihapus dari favorit.');
    }
}
