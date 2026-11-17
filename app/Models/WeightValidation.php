<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WeightValidation extends Model
{
    protected $guarded = [];

    protected $casts = ['is_simulated' => 'boolean', 'validated_at' => 'datetime'];

    public function production()
    {
        return $this->belongsTo(Production::class);
    }

    public function step()
    {
        return $this->belongsTo(Step::class);
    }

    public function workstation()
    {
        return $this->belongsTo(Workstation::class);
    }

    public function sensorReading()
    {
        return $this->belongsTo(SensorReading::class);
    }
}
