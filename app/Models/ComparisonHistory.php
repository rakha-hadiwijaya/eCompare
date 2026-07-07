<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ComparisonHistory extends Model
{
    protected $fillable = ['user_id', 'comparison_name', 'budget', 'recommendation', 'economic_score'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(ComparisonItem::class, 'history_id');
    }

    public function vehicles()
    {
        return $this->belongsToMany(Vehicle::class, 'comparison_items', 'history_id', 'vehicle_id')->withPivot('economic_score');
    }
}
