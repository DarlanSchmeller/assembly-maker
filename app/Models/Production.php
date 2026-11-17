<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Production extends Model
{
    protected $guarded = [];

    protected $casts = ['start' => 'datetime', 'end' => 'datetime'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function productionLine()
    {
        return $this->belongsTo(ProductionLine::class, 'production_lines_id');
    }

    public function stepsExecutions()
    {
        return $this->hasMany(StepExecution::class);
    }

    public function weightValidations()
    {
        return $this->hasMany(WeightValidation::class);
    }

    public function occurrences()
    {
        return $this->hasMany(Occurrence::class);
    }
}
