<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SensorReading extends Model
{
    protected $guarded = [];

    protected $casts = ['is_stable' => 'boolean', 'is_simulated' => 'boolean', 'raw_payload' => 'array', 'measured_at' => 'datetime'];

    public function workstation()
    {
        return $this->belongsTo(Workstation::class);
    }

    public function production()
    {
        return $this->belongsTo(Production::class);
    }
}
