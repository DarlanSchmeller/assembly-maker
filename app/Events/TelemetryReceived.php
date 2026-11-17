<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TelemetryReceived implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $reading;

    public $validation;

    public $station_code;

    public function __construct($reading, $validation = null, $station_code = null)
    {
        $this->reading = $reading;
        $this->validation = $validation;
        $this->station_code = $station_code ?? ($reading->workstation ? $reading->workstation->code : 'unknown');
    }

    public function broadcastOn(): array
    {
        return [
            new Channel('workstation.'.$this->station_code),
            new Channel('production'),
        ];
    }
}
