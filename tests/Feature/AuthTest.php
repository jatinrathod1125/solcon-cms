<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Run seeders to set up roles and permissions
        $this->seed();
    }

    public function test_guest_is_redirected_to_login(): void
    {
        $response = $this->get('/');
        $response->assertRedirect('/login');

        $response = $this->get('/admin/dashboard');
        $response->assertRedirect('/login');

        $response = $this->get('/supervisor/dashboard');
        $response->assertRedirect('/login');
    }

    public function test_login_screen_can_be_rendered(): void
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
    }

    public function test_user_cannot_authenticate_with_invalid_password(): void
    {
        $response = $this->post('/login', [
            'email' => 'admin@solcon.com',
            'password' => 'wrong-password',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_admin_is_redirected_to_admin_dashboard(): void
    {
        $response = $this->post('/login', [
            'email' => 'admin@solcon.com',
            'password' => 'password',
        ]);

        $response->assertRedirect('/admin/dashboard');
        $this->assertAuthenticated();
    }

    public function test_supervisor_is_redirected_to_supervisor_dashboard(): void
    {
        $response = $this->post('/login', [
            'email' => 'supervisor@solcon.com',
            'password' => 'password',
        ]);

        $response->assertRedirect('/supervisor/dashboard');
        $this->assertAuthenticated();
    }

    public function test_admin_cannot_access_supervisor_dashboard(): void
    {
        $admin = User::where('email', 'admin@solcon.com')->first();

        $response = $this->actingAs($admin)->get('/supervisor/dashboard');
        $response->assertStatus(403);
    }

    public function test_supervisor_cannot_access_admin_dashboard(): void
    {
        $supervisor = User::where('email', 'supervisor@solcon.com')->first();

        $response = $this->actingAs($supervisor)->get('/admin/dashboard');
        $response->assertStatus(403);
    }

    public function test_user_can_logout(): void
    {
        $admin = User::where('email', 'admin@solcon.com')->first();

        $response = $this->actingAs($admin)->post('/logout');
        $response->assertRedirect('/login');
        $this->assertGuest();
    }
}
