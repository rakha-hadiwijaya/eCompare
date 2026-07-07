<?php

namespace App\Http\Controllers;

use App\Models\Manufacturer;
use App\Models\Vehicle;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $query = Vehicle::active()->with('manufacturer');
        $query->when($request->vehicle_type, fn ($q, $v) => $q->where('vehicle_type', $v))
            ->when($request->fuel_type, fn ($q, $v) => $q->where('fuel_type', $v))
            ->when($request->manufacturer_id, fn ($q, $v) => $q->where('manufacturer_id', $v))
            ->when($request->model, fn ($q, $v) => $q->where('model', 'like', "%{$v}%"))
            ->when($request->year, fn ($q, $v) => $q->where('year', $v))
            ->when($request->budget, fn ($q, $v) => $q->where('new_price', '<=', $v));
        $sort = in_array($request->sort, ['new_price', 'used_price', 'year', 'model']) ? $request->sort : 'model';
        $direction = $request->direction === 'desc' ? 'desc' : 'asc';

        return view('search.index', [
            'vehicles' => $query->orderBy($sort, $direction)->paginate(12)->withQueryString(),
            'manufacturers' => Manufacturer::where('status', true)->orderBy('name')->get(),
            'fuels' => Vehicle::active()->distinct()->orderBy('fuel_type')->pluck('fuel_type'),
            'years' => Vehicle::active()->distinct()->orderByDesc('year')->pluck('year'),
        ]);
    }

    public function show(Vehicle $vehicle)
    {
        abort_unless($vehicle->status, 404);

        return view('search.show',compact('vehicle'));
    }
}
