<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class MiddlewareTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function guest_middleware_redirects_authenticated_users()
    {
        $user = User::factory()->create();

        $guestRoutes = [
            '/login',
            '/register',
            '/auth/google',
        ];

        foreach ($guestRoutes as $route) {
            $response = $this->actingAs($user)->get($route);
            $response->assertRedirect('/dashboard');
        }
    }

    #[Test]
    public function guest_middleware_allows_unauthenticated_users()
    {
        $guestRoutes = [
            '/login' => 'Auth/Login',
            '/register' => 'Auth/Register',
        ];

        foreach ($guestRoutes as $route => $component) {
            $response = $this->get($route);
            $response->assertStatus(200);
            if ($component) {
                $response->assertInertia(fn ($page) => $page->component($component));
            }
        }
    }

    #[Test]
    public function auth_middleware_redirects_guests_to_login()
    {
        $protectedRoutes = [
            '/dashboard',
            '/account',
        ];

        foreach ($protectedRoutes as $route) {
            $response = $this->get($route);
            $response->assertRedirect('/login');
        }
    }

    #[Test]
    public function auth_middleware_allows_authenticated_users()
    {
        $user = User::factory()->create();

        $protectedRoutes = [
            '/dashboard' => 'Dashboard',
            '/account' => 'Account',
        ];

        foreach ($protectedRoutes as $route => $component) {
            $response = $this->actingAs($user)->get($route);
            $response->assertStatus(200);
            $response->assertInertia(fn ($page) => $page->component($component));
        }
    }

    #[Test]
    public function logout_requires_post_method()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/logout');

        $response->assertStatus(405); // Method Not Allowed
    }

    #[Test]
    public function logout_requires_authentication()
    {
        $response = $this->post('/logout');

        $response->assertRedirect('/login');
    }

    #[Test]
    public function logout_invalidates_session()
    {
        $user = User::factory()->create();

        // Login and get session ID
        $this->actingAs($user);
        $initialSessionId = session()->getId();

        // Logout
        $response = $this->post('/logout');

        $response->assertRedirect('/');
        $this->assertGuest();

        // Start new session and verify it's different
        $this->get('/');
        $newSessionId = session()->getId();
        $this->assertNotEquals($initialSessionId, $newSessionId);
    }
}
