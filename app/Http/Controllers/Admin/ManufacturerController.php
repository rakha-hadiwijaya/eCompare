<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Manufacturer;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ManufacturerController extends Controller
{
    public function index()
    {
        return view('admin.manufacturers.index', ['manufacturers' => Manufacturer::withCount('vehicles')->orderBy('name')->paginate(20)]);
    }

    public function create()
    {
        return view('admin.manufacturers.form', ['manufacturer' => new Manufacturer]);
    }

    public function store(Request $request)
    {
        Manufacturer::create($this->validated($request));

        return redirect()->route('admin.manufacturers.index')->with('success', 'Manufacturer ditambahkan.');
    }

    public function edit(Manufacturer $manufacturer)
    {
        return view('admin.manufacturers.form', compact('manufacturer'));
    }

    public function update(Request $request, Manufacturer $manufacturer)
    {
        $manufacturer->update($this->validated($request, $manufacturer));

        return redirect()->route('admin.manufacturers.index')->with('success', 'Manufacturer diperbarui.');
    }

    public function destroy(Manufacturer $manufacturer)
    {
        abort_if($manufacturer->vehicles()->exists(), 422, 'Manufacturer masih memiliki kendaraan.');
        $manufacturer->delete();

        return back()->with('success', 'Manufacturer dihapus.');
    }

    private function validated(Request $request, ?Manufacturer $manufacturer = null)
    {
        return $request->validate(['name' => ['required', 'max:100', Rule::unique('manufacturers')->where(fn ($q) => $q->where('vehicle_type', $request->vehicle_type))->ignore($manufacturer)], 'vehicle_type' => ['required', 'in:car,motorcycle'], 'country' => ['nullable', 'max:100'], 'status' => ['required', 'boolean']]);
    }
}
