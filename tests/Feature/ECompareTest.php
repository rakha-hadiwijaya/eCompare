<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Vehicle;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ECompareTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
    }

    public function test_public_pages_and_guest_rules(): void
    {
        $this->get('/')->assertOk()->assertSee('E-Compare');
        $this->get('/search?vehicle_type=car&fuel_type=Bensin')->assertOk()->assertSee('Katalog Kendaraan');
        $this->get('/compare')->assertRedirect('/login');
    }

    public function test_user_can_save_a_two_vehicle_comparison(): void
    {
        $user = User::where('email', 'user@ecompare.test')->firstOrFail();
        $ids = Vehicle::where('vehicle_type', 'car')->take(2)->pluck('id')->all();
        $this->actingAs($user)->post('/compare', ['vehicles' => $ids, 'comparison_name' => 'Tes Ekonomis', 'budget' => 300000000])->assertRedirect();
        $this->assertDatabaseHas('comparison_histories', ['user_id' => $user->id, 'comparison_name' => 'Tes Ekonomis']);
        $this->assertDatabaseCount('comparison_items', 2);
    }

    public function test_comparison_rejects_more_than_three_vehicles(): void
    {
        $user = User::where('email', 'user@ecompare.test')->firstOrFail();
        $ids = Vehicle::take(4)->pluck('id')->all();
        $this->actingAs($user)->post('/compare', ['vehicles' => $ids, 'comparison_name' => 'Terlalu Banyak'])->assertSessionHasErrors('vehicles');
    }

    public function test_admin_area_is_role_protected(): void
    {
        $user = User::where('email', 'user@ecompare.test')->firstOrFail();
        $admin = User::where('email', 'admin@ecompare.test')->firstOrFail();
        $this->actingAs($user)->get('/admin')->assertForbidden();
        $this->actingAs($admin)->get('/admin')->assertOk()->assertSee('Dashboard Admin');
    }

    public function test_admin_index_pages_render_without_table_column_mismatch(): void
    {
        $admin = User::where('email', 'admin@ecompare.test')->firstOrFail();

        foreach (['/admin/vehicles', '/admin/manufacturers', '/admin/users', '/admin/notifications'] as $url) {
            $this->actingAs($admin)->get($url)->assertOk();
        }

        $usersPage = $this->actingAs($admin)->get('/admin/users');
        $usersPage->assertDontSee('colspan="2"', false);

        preg_match('/<tbody>(.*?)<\/tbody>/s', $usersPage->getContent(), $tbody);
        preg_match_all('/<tr>(.*?)<\/tr>/s', $tbody[1] ?? '', $rows);

        foreach ($rows[1] as $row) {
            $this->assertSame(5, substr_count($row, '<td'), 'Setiap baris pengguna harus memiliki lima kolom DataTables.');
        }
    }
}
