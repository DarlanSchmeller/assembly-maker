<?php

namespace App\Services;

use App\Models\Production;
use App\Models\WeightValidation;

class ProductionService
{
    public function getKpis()
    {
        // Calculate production KPIs
        $completed = Production::where('status', 'completed')->count();
        $accepted = Production::where('status', 'completed')->whereDoesntHave('weightValidations', function ($q) {
            $q->where('result', 'rejected');
        })->count();
        $rejected = WeightValidation::where('result', 'rejected')->count();
        $totalAttempts = $accepted + $rejected;

        $totalTime = Production::where('status', 'completed')->sum(\DB::raw('TIMESTAMPDIFF(SECOND, start, end)'));
        $avgTime = $completed > 0 ? round($totalTime / $completed) : 0;

        return [
            'completed' => $completed,
            'accepted' => $accepted,
            'rejected' => $rejected,
            'success_rate' => $totalAttempts > 0 ? round(($accepted / $totalAttempts) * 100, 1) : 0,
            'avg_cycle_time' => $avgTime,
        ];
    }
}
