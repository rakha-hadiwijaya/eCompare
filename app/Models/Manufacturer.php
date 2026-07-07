<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Manufacturer extends Model
{
    protected $fillable = ['name', 'vehicle_type', 'country', 'status'];

    protected $casts = ['status' => 'boolean'];

    public function vehicles()
    {
        return $this->hasMany(Vehicle::class);
    }
}
