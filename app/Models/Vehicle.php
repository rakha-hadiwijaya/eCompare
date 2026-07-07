<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    protected $guarded = [];

    protected $casts = ['status' => 'boolean', 'fuel_efficiency' => 'float', 'ev_range' => 'float', 'depreciation_rate' => 'float'];

    public function manufacturer()
    {
        return $this->belongsTo(Manufacturer::class);
    }

    public function favoredBy()
    {
        return $this->belongsToMany(User::class, 'favorites')->withTimestamps();
    }

    public function scopeActive(Builder $query)
    {
        return $query->where('status', true)->whereHas('manufacturer', fn ($q) => $q->where('status', true));
    }

    public function getDisplayNameAttribute()
    {
        return "{$this->manufacturer?->name} {$this->model} {$this->variant}";
    }

    public function getEfficiencyLabelAttribute()
    {
        return $this->fuel_type === 'Listrik' ? number_format($this->ev_range, 0).' km' : number_format($this->fuel_efficiency, 1).' km/l';
    }
}
