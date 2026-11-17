<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class StationStatusChanged implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public string $station_code;

    public string $status;

    public ?string $operator_name;

    public function __construct(string $stationCode, string $status, ?string $operatorName = null)
    {
        $this->station_code = $stationCode;
        $this->status = $status;
        $this->operator_name = $operatorName;
    }

    public function broadcastOn(): array
    {
        return [
            new Channel('production'),
            new Channel('workstation.'.$this->station_code),
        ];
    }
}
