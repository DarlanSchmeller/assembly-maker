<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ProductionCompleted implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $production;

    public $time;

    public function __construct($production, $time)
    {
        $this->production = $production;
        $this->time = $time;
    }

    public function broadcastOn(): array
    {
        return [
            new Channel('production'),
        ];
    }
}
