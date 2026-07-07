<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserPreference extends Model
{
    protected $fillable = ['user_id', 'favorite_vehicle_type', 'favorite_brand', 'budget_range', 'preferred_fuel'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
