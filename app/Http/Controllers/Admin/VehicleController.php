<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Manufacturer;
use App\Models\Vehicle;
use Illuminate\Http\Request;

class VehicleController extends Controller
{
    public function index(Request $request)
    {
        $q = Vehicle::with('manufacturer')->when($request->q, fn ($x, $v) => $x->where(fn ($w) => $w->where('model', 'like', "%{$v}%")->orWhere('variant', 'like', "%{$v}%")))->when($request->vehicle_type, fn ($x, $v) => $x->where('vehicle_type', $v));

        return view('admin.vehicles.index', ['vehicles' => $q->latest()->paginate(20)->withQueryString()]);
    }

    public function create()
    {
        return view('admin.vehicles.form', ['vehicle' => new Vehicle, 'manufacturers' => Manufacturer::where('status', true)->orderBy('name')->get()]);
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('vehicles', 'public');
        } Vehicle::create($data);

        return redirect()->route('admin.vehicles.index')->with('success', 'Kendaraan ditambahkan.');
    }

    public function edit(Vehicle $vehicle)
    {
        return view('admin.vehicles.form', ['vehicle' => $vehicle, 'manufacturers' => Manufacturer::orderBy('name')->get()]);
    }

    public function update(Request $request, Vehicle $vehicle)
    {
        $data = $this->validated($request);
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('vehicles', 'public');
        } $vehicle->update($data);

        return redirect()->route('admin.vehicles.index')->with('success', 'Kendaraan diperbarui.');
    }

    public function destroy(Vehicle $vehicle)
    {
        $vehicle->delete();

        return back()->with('success', 'Kendaraan dihapus.');
    }

    private function validated(Request $request)
    {
        abort_unless(
            Manufacturer::whereKey($request->manufacturer_id)->where('vehicle_type', $request->vehicle_type)->exists(),
            422,
            'Tipe manufacturer harus sesuai dengan tipe kendaraan.'
        );

        return $request->validate([
            'manufacturer_id' => ['required', 'exists:manufacturers,id'], 'vehicle_type' => ['required', 'in:car,motorcycle'], 'model' => ['required', 'max:100'], 'variant' => ['required', 'max:150'], 'year' => ['required', 'integer', 'min:1900', 'max:'.(date('Y') + 1)], 'fuel_type' => ['required', 'max:50'], 'transmission' => ['nullable', 'max:50'], 'category' => ['nullable', 'max:80'], 'engine_cc' => ['nullable', 'integer', 'min:0'], 'power_hp' => ['nullable', 'numeric', 'min:0'], 'torque_nm' => ['nullable', 'numeric', 'min:0'], 'seat_capacity' => ['nullable', 'integer', 'min:1', 'max:100'], 'fuel_tank_capacity' => ['nullable', 'numeric', 'min:0'], 'fuel_efficiency' => ['nullable', 'numeric', 'min:0'], 'ev_range' => ['nullable', 'numeric', 'min:0'], 'annual_tax' => ['required', 'integer', 'min:0'], 'annual_service_cost' => ['required', 'integer', 'min:0'], 'depreciation_rate' => ['required', 'numeric', 'min:0', 'max:100'], 'new_price' => ['required', 'integer', 'min:0'], 'used_price' => ['required', 'integer', 'min:0'], 'image' => ['nullable', 'image', 'max:3072'], 'status' => ['required', 'boolean']]);
    }
}
