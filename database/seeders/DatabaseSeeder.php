<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductionLine;
use App\Models\Step;
use App\Models\User;
use App\Models\Workstation;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $product = Product::firstOrCreate(
            ['code' => 'IOT-001'],
            [
                'name' => 'Peça de Teste IoT',
                'description' => 'Peça de demonstração para bancada IoT',
                'expected_weight_grams' => 100,
                'weight_tolerance_grams' => 5,
            ]
        );

        $line = ProductionLine::firstOrCreate(
            ['name' => 'Linha de Montagem 1'],
            ['description' => 'Linha principal']
        );

        $station1 = Workstation::firstOrCreate(
            ['code' => 'bancada_01'],
            [
                'production_line_id' => $line->id,
                'name' => 'Bancada 01 (Base & Sensor)',
                'has_scale' => true,
                'status' => 'offline',
            ]
        );

        $station2 = Workstation::firstOrCreate(
            ['code' => 'bancada_02'],
            [
                'production_line_id' => $line->id,
                'name' => 'Bancada 02 (Placa & Acabamento)',
                'has_scale' => true,
                'status' => 'offline',
            ]
        );

        Step::firstOrCreate(
            ['production_line_id' => $line->id, 'order' => 1],
            [
                'workstation_id' => $station1->id,
                'name' => 'Montagem da Base',
                'time_cycle_goal' => 60,
                'expected_weight_grams' => 100,
                'weight_tolerance_grams' => 5,
                'requires_weight_validation' => true,
            ]
        );

        Step::firstOrCreate(
            ['production_line_id' => $line->id, 'order' => 2],
            [
                'workstation_id' => $station2->id,
                'name' => 'Encaixe do Módulo & Acabamento',
                'time_cycle_goal' => 45,
                'expected_weight_grams' => 150,
                'weight_tolerance_grams' => 6,
                'requires_weight_validation' => true,
            ]
        );

        User::firstOrCreate(
            ['email' => 'supervisor@renault.com'],
            [
                'name' => 'Supervisor Renault',
                'password' => bcrypt('password'),
                'role' => 'supervisor',
            ]
        );

        // Operador Bancada 1
        User::updateOrCreate(
            ['email' => 'operador1@renault.com'],
            [
                'name' => 'Operador Matheus (Bancada 1)',
                'password' => bcrypt('password'),
                'role' => 'operator',
                'workstation_id' => $station1->id,
            ]
        );

        // Operador Bancada 2
        User::updateOrCreate(
            ['email' => 'operador2@renault.com'],
            [
                'name' => 'Operador Lucas (Bancada 2)',
                'password' => bcrypt('password'),
                'role' => 'operator',
                'workstation_id' => $station2->id,
            ]
        );

        // Legacy / generic operator
        User::updateOrCreate(
            ['email' => 'operador@renault.com'],
            [
                'name' => 'Operador Bancada 01',
                'password' => bcrypt('password'),
                'role' => 'operator',
                'workstation_id' => $station1->id,
            ]
        );
    }
}
