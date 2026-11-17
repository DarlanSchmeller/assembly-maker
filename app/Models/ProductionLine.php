<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductionLine extends Model
{
    protected $guarded = [];

    public function workstations()
    {
        return $this->hasMany(Workstation::class);
    }

    public function steps()
    {
        return $this->hasMany(Step::class);
    }

    public function productions()
    {
        return $this->hasMany(Production::class, 'production_lines_id');
    }
}
