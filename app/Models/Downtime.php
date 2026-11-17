<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Downtime extends Model
{
    protected $guarded = [];

    protected $casts = ['start' => 'datetime', 'end' => 'datetime'];

    public function productionLine()
    {
        return $this->belongsTo(ProductionLine::class);
    }

    public function step()
    {
        return $this->belongsTo(Step::class);
    }
}
