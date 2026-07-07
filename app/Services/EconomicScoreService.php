<?php

namespace App\Services;

use Illuminate\Support\Collection;

class EconomicScoreService
{
    public function calculate(Collection $vehicles): Collection
    {
        $maxFuelEfficiency = max(1, (float) $vehicles->where('fuel_type', '!=', 'Listrik')->max('fuel_efficiency'));
        $maxEvRange = max(1, (float) $vehicles->where('fuel_type', 'Listrik')->max('ev_range'));
        $minNew = max(1, (float) $vehicles->min('new_price'));
        $minUsed = max(1, (float) $vehicles->min('used_price'));
        $positiveTaxes = $vehicles->pluck('annual_tax')->filter(fn ($v) => $v > 0);
        $minTax = max(1, (float) ($positiveTaxes->min() ?: 1));
        $positiveDepreciation = $vehicles->pluck('depreciation_rate')->filter(fn ($v) => $v > 0);
        $minDepreciation = max(0.01, (float) ($positiveDepreciation->min() ?: 0.01));

        return $vehicles->mapWithKeys(function ($vehicle) use ($maxFuelEfficiency, $maxEvRange, $minNew, $minUsed, $minTax, $minDepreciation) {
            $efficiency = $vehicle->fuel_type === 'Listrik' ? $vehicle->ev_range : $vehicle->fuel_efficiency;
            $efficiencyBenchmark = $vehicle->fuel_type === 'Listrik' ? $maxEvRange : $maxFuelEfficiency;
            $score = 35 * ($minNew / max(1, $vehicle->new_price))
                + 25 * ($minUsed / max(1, $vehicle->used_price))
                + 20 * ((float) $efficiency / $efficiencyBenchmark)
                + 10 * ($minTax / max(1, $vehicle->annual_tax))
                + 10 * ($minDepreciation / max(0.01, $vehicle->depreciation_rate));

            return [$vehicle->id => round(min(100, max(0, $score)), 2)];
        });
    }

    public function category(float $score): string
    {
        return match (true) {
            $score >= 90 => 'Excellent Value',
            $score >= 80 => 'Recommended',
            $score >= 70 => 'Good',
            $score >= 60 => 'Average',
            default => 'Premium Choice',
        };
    }
}
