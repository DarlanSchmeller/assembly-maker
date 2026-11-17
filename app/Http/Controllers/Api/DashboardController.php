<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Alert;
use App\Models\StepExecution;
use App\Models\WeightValidation;
use App\Models\Workstation;
use App\Services\ProductionService;

class DashboardController extends Controller
{
    public function kpis(ProductionService $productionService)
    {
        return response()->json($productionService->getKpis());
    }

    public function stations()
    {
        return response()->json(Workstation::with(['steps', 'operators'])->get());
    }

    public function workbenchesAnalytics()
    {
        $stations = Workstation::with(['steps', 'operators'])->get()->map(function ($st) {
            $totalValidations = WeightValidation::where('workstation_id', $st->id)->count();
            $approved = WeightValidation::where('workstation_id', $st->id)->where('result', 'approved')->count();
            $rejected = WeightValidation::where('workstation_id', $st->id)->where('result', 'rejected')->count();
            $successRate = $totalValidations > 0 ? round(($approved / $totalValidations) * 100, 1) : 100;

            $stepIds = $st->steps->pluck('id');
            $avgCycle = $st->steps
                ->whereNotNull('time_cycle_goal')
                ->avg('time_cycle_goal');

            $alertsCount = Alert::where('workstation_id', $st->id)->where('is_active', true)->count();
            $lastAlert = Alert::where('workstation_id', $st->id)->latest()->first();

            return [
                'id' => $st->id,
                'name' => $st->name,
                'code' => $st->code,
                'status' => $st->status,
                'operator' => $st->operators->first()?->name ?? 'Sem operador ativo',
                'total_pieces' => $totalValidations,
                'approved' => $approved,
                'rejected' => $rejected,
                'success_rate' => $successRate,
                'avg_cycle_time' => $avgCycle ? round($avgCycle, 1) : 52.0,
                'active_alerts' => $alertsCount,
                'last_alert' => $lastAlert ? [
                    'title' => $lastAlert->title,
                    'description' => $lastAlert->description,
                    'created_at' => $lastAlert->created_at->format('H:i:s'),
                ] : null,
            ];
        });

        return response()->json($stations);
    }

    public function recentAlerts()
    {
        $alerts = Alert::with('workstation')
            ->latest()
            ->take(15)
            ->get()
            ->map(function ($alert) {
                return [
                    'id' => $alert->id,
                    'station_name' => $alert->workstation?->name ?? 'Geral',
                    'station_code' => $alert->workstation?->code ?? 'unknown',
                    'title' => $alert->title,
                    'description' => $alert->description,
                    'severity' => $alert->severity,
                    'time' => $alert->created_at->format('H:i:s'),
                ];
            });

        return response()->json($alerts);
    }
}
