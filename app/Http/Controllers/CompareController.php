<?php

namespace App\Http\Controllers;

use App\Models\ComparisonHistory;
use App\Models\Vehicle;
use App\Services\EconomicScoreService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CompareController extends Controller
{
    public function index(Request $request, EconomicScoreService $service)
    {
        $ids = array_values(array_unique(array_filter((array) $request->input('vehicles', []))));
        $vehicles = Vehicle::active()->with('manufacturer')->whereIn('id', $ids)->get();
        $scores = $vehicles->count() ? $service->calculate($vehicles) : collect();

        return view('compare.index', compact('vehicles', 'scores', 'service'));
    }

    public function store(Request $request, EconomicScoreService $service)
    {
        $data = $request->validate(['vehicles' => ['required', 'array', 'min:2', 'max:3'], 'vehicles.*' => ['distinct', 'exists:vehicles,id'], 'comparison_name' => ['required', 'string', 'max:100'], 'budget' => ['nullable', 'integer', 'min:0']]);
        $vehicles = Vehicle::active()->with('manufacturer')->whereIn('id', $data['vehicles'])->get();
        abort_if($vehicles->count() !== count($data['vehicles']), 422, 'Kendaraan tidak valid atau tidak aktif.');
        $scores = $service->calculate($vehicles);
        $winner = $vehicles->sortByDesc(fn ($v) => $scores[$v->id])->first();
        $history = DB::transaction(function () use ($request, $data, $scores, $winner, $service) {
            $history = $request->user()->comparisons()->create(['comparison_name' => $data['comparison_name'], 'budget' => $data['budget'] ?? null, 'economic_score' => $scores[$winner->id], 'recommendation' => $winner->display_name.' — '.$service->category($scores[$winner->id])]);
            foreach ($scores as $vehicleId => $score) {
                $history->items()->create(['vehicle_id' => $vehicleId, 'economic_score' => $score]);
            }

            return $history;
        });

        return redirect()->route('history.show', $history)->with('success', 'Perbandingan berhasil disimpan.');
    }

    public function history()
    {
        return view('history.index', ['histories' => request()->user()->comparisons()->with('vehicles.manufacturer')->latest()->paginate(10)]);
    }

    public function show(ComparisonHistory $history, EconomicScoreService $service)
    {
        abort_unless($history->user_id === request()->user()->id || request()->user()->isAdmin(), 403);
        $history->load('vehicles.manufacturer');

        return view('history.show', compact('history', 'service'));
    }

    public function destroy(ComparisonHistory $history)
    {
        abort_unless($history->user_id === request()->user()->id || request()->user()->isAdmin(), 403);
        $history->delete();

        return back()->with('success', 'Riwayat dihapus.');
    }

    public function pdf(ComparisonHistory $history, EconomicScoreService $service)
    {
        abort_unless($history->user_id === request()->user()->id || request()->user()->isAdmin(), 403);
        $history->load('vehicles.manufacturer');

        return app('dompdf.wrapper')->loadView('history.pdf', compact('history', 'service'))->download('e-compare-'.$history->id.'.pdf');
    }
}
