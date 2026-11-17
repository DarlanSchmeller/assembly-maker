<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Step extends Model
{
    protected $guarded = [];

    protected $casts = ['components_needed' => 'array', 'requires_weight_validation' => 'boolean'];

    public function productionLine()
    {
        return $this->belongsTo(ProductionLine::class);
    }

    public function workstation()
    {
        return $this->belongsTo(Workstation::class);
    }

    public function instructionsData()
    {
        return $this->hasMany(AssemblyInstruction::class);
    }
}
