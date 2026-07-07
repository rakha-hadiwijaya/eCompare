<?php

namespace Database\Seeders;

use App\Models\Notification;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $userRole = Role::firstOrCreate(['name' => 'user']);

        User::updateOrCreate(['email' => 'admin@ecompare.test'], ['role_id' => $adminRole->id, 'name' => 'Administrator', 'password' => 'password', 'is_active' => true]);
        User::updateOrCreate(['email' => 'user@ecompare.test'], ['role_id' => $userRole->id, 'name' => 'Demo User', 'password' => 'password', 'is_active' => true]);

        $this->call(VehicleSeeder::class);

        Notification::firstOrCreate(['title' => 'Selamat datang di E-Compare'], ['content' => 'Bandingkan hingga tiga kendaraan dan temukan pilihan paling ekonomis untuk kebutuhan Anda.', 'status' => true]);
        Notification::firstOrCreate(['title' => 'Dataset kendaraan 2025 tersedia'], ['content' => 'Katalog mobil dan motor terbaru sudah dapat dicari dan dibandingkan.', 'status' => true]);
    }
}
