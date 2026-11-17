<?php

use App\Events\ProductionCompleted;
use App\Events\TelemetryReceived;
use App\Http\Controllers\Api\DashboardController;
use App\Models\Alert;
use App\Models\Product;
use App\Models\Production;
use App\Models\SensorReading;
use App\Models\Step;
use App\Models\Workstation;
use App\Services\WeightValidationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

Route::get('/supervisor/kpis', [DashboardController::class, 'kpis']);
Route::get('/supervisor/stations', [DashboardController::class, 'stations']);

Route::post('/operator/start-production', function (Request $request) {
    $code = $request->input('workstation_code');

    // For prototype, just return success
    return response()->json(['success' => true]);
});

Route::post('/simulate/telemetry', function (Request $request) {
    $code = $request->input('station', 'bancada_01');
    $weight = $request->input('weight', 100);

    // Simulate MQTT publish (since we might not have a broker running locally for the UI testing)
    // We can just call the service or event directly to simulate it for the prototype

    $station = Workstation::where('code', $code)->first();
    if (! $station) {
        return;
    }

    $reading = SensorReading::create([
        'workstation_id' => $station->id,
        'value' => $weight,
        'unit' => 'g',
        'is_stable' => true,
        'is_simulated' => true,
        'measured_at' => now(),
    ]);

    $step = Step::where('workstation_id', $station->id)->where('requires_weight_validation', true)->first();

    $validation = null;
    if ($step) {
        $validationService = new WeightValidationService;
        $validation = $validationService->validate($reading, $step);
    }

    event(new TelemetryReceived($reading, $validation, $code));

    // If rejected, create an Alert for the supervisor
    if ($validation && $validation->result === 'rejected') {
        Alert::create([
            'workstation_id' => $station->id,
            'type' => 'weight_out_of_range',
            'severity' => 'warning',
            'title' => "Desvio de peso na {$station->name}",
            'description' => "Peso medido: {$reading->value}g. Tolerância esperada: {$step->expected_weight_grams}g (±{$step->weight_tolerance_grams}g).",
            'is_active' => true,
        ]);
    }

    // If approved, complete a production cycle
    if ($validation && $validation->result === 'approved') {
        $product = Product::first();
        $prod = Production::create([
            'product_id' => $product ? $product->id : 1,
            'production_lines_id' => $station->production_line_id ?? 1,
            'tracking_code' => 'IOT-'.strtoupper(Str::random(8)),
            'status' => 'completed',
            'start' => now()->subSeconds(rand(45, 120)),
            'end' => now(),
        ]);
        $validation->update(['production_id' => $prod->id]);
        $time = $prod->end->diffInSeconds($prod->start);
        event(new ProductionCompleted($prod, $time));
    }

    return response()->json(['success' => true]);
});

Route::get('/supervisor/workbenches-data', [DashboardController::class, 'workbenchesAnalytics']);
Route::get('/supervisor/recent-alerts', [DashboardController::class, 'recentAlerts']);
