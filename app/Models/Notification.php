<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $fillable = ['title', 'content', 'status'];

    protected $casts = ['status' => 'boolean'];

    public function users()
    {
        return $this->belongsToMany(User::class)->withPivot('read_at');
    }
}
