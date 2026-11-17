<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Alert extends Model
{
    protected $guarded = [];

    protected $casts = ['acknowledged' => 'boolean', 'acknowledged_at' => 'datetime', 'is_active' => 'boolean'];

    public function workstation()
    {
        return $this->belongsTo(Workstation::class);
    }

    public function production()
    {
        return $this->belongsTo(Production::class);
    }

    public function acknowledgedBy()
    {
        return $this->belongsTo(User::class, 'acknowledged_by');
    }
}
