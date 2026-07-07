<?php

namespace Database\Seeders;

use App\Models\Manufacturer;
use App\Models\Vehicle;
use Illuminate\Database\Seeder;

class VehicleSeeder extends Seeder
{
    private array $countries = ['Toyota' => 'Jepang', 'Honda' => 'Jepang', 'Daihatsu' => 'Jepang', 'Suzuki' => 'Jepang', 'Mitsubishi' => 'Jepang', 'Nissan' => 'Jepang', 'Mazda' => 'Jepang', 'Lexus' => 'Jepang', 'Isuzu' => 'Jepang', 'Yamaha' => 'Jepang', 'Kawasaki' => 'Jepang', 'Hyundai' => 'Korea Selatan', 'Kia' => 'Korea Selatan', 'BMW' => 'Jerman', 'Mercedes-Benz' => 'Jerman', 'Wuling' => 'Tiongkok', 'BYD' => 'Tiongkok', 'Chery' => 'Tiongkok', 'DFSK' => 'Tiongkok', 'GWM' => 'Tiongkok', 'MG' => 'Inggris', 'Vespa' => 'Italia', 'Benelli' => 'Italia'];

    public function run(): void
    {
        $this->import(database_path('data/cars.csv'), 'car');
        $this->import(database_path('data/motorcycles.csv'), 'motorcycle');
    }

    private function import(string $path, string $type): void
    {
        $handle = fopen($path, 'r');
        $headers = fgetcsv($handle);
        while (($values = fgetcsv($handle)) !== false) {
            if (count($values) !== count($headers)) {
                continue;
            }
            $row = array_combine($headers, $values);
            $brand = trim($row['Brand']);
            $manufacturer = Manufacturer::firstOrCreate(['name' => $brand, 'vehicle_type' => $type], ['country' => $this->countries[$brand] ?? null, 'status' => true]);
            $new = (int) $row['Harga Baru (Rp)'];
            $used = (int) $row['Harga Second (Rp)'];
            $fuel = $row[array_values(array_filter($headers, fn ($h) => str_starts_with($h, 'Jenis Energi')))[0]];
            $metric = $row['Konsumsi BBM atau Jarak Tempuh EV'];
            $number = (float) preg_replace('/[^0-9.]/', '', $metric);
            $depreciation = $new > 0 ? round((($new - $used) / $new) * 100, 2) : 0;
            $taxRate = $fuel === 'Listrik' ? 0.002 : ($type === 'motorcycle' ? 0.015 : 0.02);
            Vehicle::updateOrCreate([
                'manufacturer_id' => $manufacturer->id, 'model' => $row[$type === 'car' ? 'Nama Mobil' : 'Nama Motor'], 'variant' => $row['Seri/Varian'], 'year' => (int) $row['Tahun'],
            ], [
                'vehicle_type' => $type, 'fuel_type' => $fuel, 'transmission' => $row['Transmisi'] ?: null, 'category' => $row['Kategori'] ?: null,
                'engine_cc' => $type === 'motorcycle' ? (int) $row['CC'] : null, 'fuel_efficiency' => $fuel === 'Listrik' ? null : $number, 'ev_range' => $fuel === 'Listrik' ? $number : null,
                'annual_tax' => (int) round($new * $taxRate, -3), 'annual_service_cost' => (int) round($new * ($type === 'motorcycle' ? 0.018 : 0.012), -3),
                'depreciation_rate' => $depreciation, 'new_price' => $new, 'used_price' => $used, 'status' => true,
            ]);
        }
        fclose($handle);
    }
}
