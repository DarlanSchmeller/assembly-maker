<?php

namespace App\Services;

use App\Models\SensorReading;
use App\Models\Step;
use App\Models\WeightValidation;

class WeightValidationService
{
    public function validate(SensorReading $reading, Step $step)
    {
        $expected = $step->expected_weight_grams;
        $tolerance = $step->weight_tolerance_grams;
        $deviation = abs($reading->value - $expected);

        $result = $deviation <= $tolerance ? 'approved' : 'rejected';
        $reason = $result === 'rejected' ? 'Weight out of tolerance range.' : null;

        return WeightValidation::create([
            'production_id' => $reading->production_id,
            'step_id' => $step->id,
            'workstation_id' => $reading->workstation_id,
            'sensor_reading_id' => $reading->id,
            'measured_weight' => $reading->value,
            'expected_weight' => $expected,
            'tolerance' => $tolerance,
            'deviation' => $deviation,
            'result' => $result,
            'rejection_reason' => $reason,
            'is_simulated' => $reading->is_simulated,
            'validated_at' => now(),
        ]);
    }
}
