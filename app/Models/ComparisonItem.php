<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ComparisonItem extends Model
{
    public $timestamps = false;

    protected $fillable = ['history_id', 'vehicle_id', 'economic_score'];

    public function history()
    {
        return $this->belongsTo(ComparisonHistory::class, 'history_id');
    }

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }
}
