<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class AuthenticationFlowTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function complete_authentication_flow_works()
    {
        // Test registration
        $registerResponse = $this->post('/register', [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
        ]);

        $registerResponse->assertRedirect('/dashboard');
        $this->assertAuthenticated();

        // Test logout
        $this->post('/logout');
        $this->assertGuest();

        // Test login
        $loginResponse = $this->post('/login', [
            'email' => 'john@example.com',
            'password' => 'Password123!',
        ]);

        $loginResponse->assertRedirect('/dashboard');
        $this->assertAuthenticated();
    }

    #[Test]
    public function password_validation_prevents_weak_passwords()
    {
        $weakPasswords = [
            '1234',       // Too short
            '12345',      // Too short 
            '123456',     // Too short
            '1234567',    // Still too short for default min:8
        ];

        foreach ($weakPasswords as $password) {
            $response = $this->post('/register', [
                'name' => 'John Doe',
                'email' => 'john@example.com',
                'password' => $password,
                'password_confirmation' => $password,
            ]);

            $response->assertSessionHasErrors(['password']);
            $this->assertGuest();
        }
    }

    #[Test]
    public function user_can_access_protected_routes_after_authentication()
    {
        $user = User::factory()->create();

        $protectedRoutes = [
            '/dashboard',
            '/account',
        ];

        foreach ($protectedRoutes as $route) {
            $response = $this->actingAs($user)->get($route);
            $response->assertStatus(200);
        }
    }
}
