<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthWorkflowTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     */
    public function test_unauthenticated_users_are_redirected_to_login(): void
    {
        $response = $this->get('/operator');
        $response->assertRedirect(route('login.form'));

        $response = $this->get('/supervisor');
        $response->assertRedirect(route('login.form'));
    }

    public function test_operator_can_login_and_access_operator_dashboard(): void
    {
        $user = User::firstOrCreate(
            ['email' => 'operador@renault.com'],
            ['name' => 'Operador Matheus', 'password' => bcrypt('password'), 'role' => 'operator']
        );

        $response = $this->post('/login', [
            'email' => 'operador@renault.com',
            'password' => 'password',
        ]);

        $response->assertRedirect(route('operator.dashboard'));
        $this->assertAuthenticatedAs($user);
    }

    public function test_supervisor_can_login_and_access_supervisor_dashboard(): void
    {
        $user = User::firstOrCreate(
            ['email' => 'supervisor@renault.com'],
            ['name' => 'Supervisor Renault', 'password' => bcrypt('password'), 'role' => 'supervisor']
        );

        $response = $this->post('/login', [
            'email' => 'supervisor@renault.com',
            'password' => 'password',
        ]);

        $response->assertRedirect(route('supervisor.dashboard'));
        $this->assertAuthenticatedAs($user);
    }
}
