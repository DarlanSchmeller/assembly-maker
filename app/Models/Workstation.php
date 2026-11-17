<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Workstation extends Model
{
    protected $guarded = [];

    protected $casts = ['has_scale' => 'boolean', 'has_led' => 'boolean', 'last_heartbeat_at' => 'datetime'];

    public function productionLine()
    {
        return $this->belongsTo(ProductionLine::class);
    }

    public function operators()
    {
        return $this->hasMany(User::class);
    }

    public function steps()
    {
        return $this->hasMany(Step::class);
    }

    public function sensorReadings()
    {
        return $this->hasMany(SensorReading::class);
    }

    public function deviceCommands()
    {
        return $this->hasMany(DeviceCommand::class);
    }

    public function activeAlerts()
    {
        return $this->hasMany(Alert::class)->where('is_active', true);
    }
}
