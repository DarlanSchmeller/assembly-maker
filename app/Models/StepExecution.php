<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StepExecution extends Model
{
    protected $table = 'steps_executions';

    protected $guarded = [];

    protected $casts = ['start' => 'datetime', 'end' => 'datetime'];

    public function production()
    {
        return $this->belongsTo(Production::class);
    }

    public function step()
    {
        return $this->belongsTo(Step::class);
    }

    public function operator()
    {
        return $this->belongsTo(Operator::class);
    }
}
