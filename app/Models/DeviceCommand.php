<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeviceCommand extends Model
{
    protected $guarded = [];

    protected $casts = ['payload' => 'array', 'published_at' => 'datetime', 'acknowledged_at' => 'datetime', 'timeout_at' => 'datetime'];

    public function workstation()
    {
        return $this->belongsTo(Workstation::class);
    }

    public function requestedBy()
    {
        return $this->belongsTo(User::class, 'requested_by');
    }
}
