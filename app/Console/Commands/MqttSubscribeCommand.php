<?php

namespace App\Console\Commands;

use App\Events\TelemetryReceived;
use App\Models\SensorReading;
use App\Models\Step;
use App\Models\Workstation;
use App\Services\StationHealthService;
use App\Services\WeightValidationService;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use PhpMqtt\Client\Facades\MQTT;

class MqttSubscribeCommand extends Command
{
    protected $signature = 'mqtt:subscribe';

    protected $description = 'Subscribe to MQTT topics and process incoming telemetry';

    public function handle(WeightValidationService $validationService, StationHealthService $healthService)
    {
        $this->info('Starting MQTT subscriber...');

        try {
            $mqtt = MQTT::connection();

            // For this prototype, we'll subscribe to all relevant topics
            // In a real scenario, we might query Workstation models and subscribe dynamically

            // From Manual: estoque/prateleira_01/telemetria
            // Prototype: fabrica/linha1/pecas, fabrica/linha1/tempo, fabrica/linha1/controle

            $mqtt->subscribe('estoque/+/telemetria', function (string $topic, string $message) use ($validationService, $healthService) {
                $this->info("Received on [$topic]: $message");
                $this->processTelemetry($topic, $message, $validationService, $healthService);
            }, 0);

            $mqtt->subscribe('fabrica/linha1/pecas', function (string $topic, string $message) {
                $this->info("Received on [$topic]: $message");
                // Prototype specific
            }, 0);

            $this->info('Listening for messages... (Press CTRL+C to stop)');
            $mqtt->loop(true);
        } catch (Exception $e) {
            $this->error('MQTT Error: '.$e->getMessage());
            Log::error('MQTT Error: '.$e->getMessage());
            // Retry logic could go here or rely on Supervisor/systemd
            sleep(5);
        }
    }

    private function processTelemetry($topic, $message, $validationService, $healthService)
    {
        // Example logic for processing the payload
        // Expected topic: estoque/prateleira_01/telemetria
        $parts = explode('/', $topic);
        $stationCode = $parts[1] ?? 'unknown';

        $healthService->updateHeartbeat($stationCode);

        $payload = json_decode($message, true);
        if (! $payload) {
            return; // invalid json
        }

        $weight = $payload['peso'] ?? $payload['weight'] ?? null;

        if ($weight !== null) {
            $station = Workstation::where('code', $stationCode)->first();
            if ($station) {
                $reading = SensorReading::create([
                    'workstation_id' => $station->id,
                    'value' => $weight,
                    'unit' => 'g',
                    'is_stable' => $payload['stable'] ?? true,
                    'raw_payload' => $payload,
                    'mqtt_topic' => $topic,
                    'measured_at' => now(),
                ]);

                // If there's an active step requiring validation for this station...
                // Mocking Step for prototype
                $step = Step::where('workstation_id', $station->id)->where('requires_weight_validation', true)->first();
                if ($step) {
                    $validationService->validate($reading, $step);
                }

                // Fire an event to update the frontend
                event(new TelemetryReceived($reading));
            }
        }
    }
}
