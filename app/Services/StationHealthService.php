<?php

namespace App\Services;

use App\Models\Workstation;

class StationHealthService
{
    public function updateHeartbeat(string $stationCode)
    {
        $station = Workstation::where('code', $stationCode)->first();
        if ($station) {
            $station->update([
                'last_heartbeat_at' => now(),
                'status' => 'online',
            ]);
        }
    }

    public function checkOfflineStations()
    {
        $threshold = now()->subMinutes(5);
        Workstation::where('last_heartbeat_at', '<', $threshold)
            ->where('status', '!=', 'offline')
            ->update(['status' => 'offline']);
    }
}
