<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Occurrence extends Model
{
    protected $guarded = [];

    protected $casts = ['start' => 'datetime', 'end' => 'datetime', 'solved' => 'boolean'];

    public function production()
    {
        return $this->belongsTo(Production::class);
    }

    public function step()
    {
        return $this->belongsTo(Step::class);
    }
}
