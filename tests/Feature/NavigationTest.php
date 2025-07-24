<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class NavigationTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function home_page_loads_for_guests()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page->component('Home'));
    }

    #[Test]
    public function authenticated_users_are_redirected_from_home_to_dashboard()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/');

        $response->assertRedirect('/dashboard');
    }

    #[Test]
    public function dashboard_requires_authentication()
    {
        $response = $this->get('/dashboard');

        $response->assertRedirect('/login');
    }

    #[Test]
    public function authenticated_users_can_access_dashboard()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/dashboard');

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page->component('Dashboard'));
    }

    #[Test]
    public function account_page_requires_authentication()
    {
        $response = $this->get('/account');

        $response->assertRedirect('/login');
    }

    #[Test]
    public function authenticated_users_can_access_account_page()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/account');

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page->component('Account'));
    }

    #[Test]
    public function guests_are_redirected_to_login_when_accessing_protected_routes()
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
    public function session_regeneration_works_properly()
    {
        $user = User::factory()->create();

        // Get initial session ID
        $this->get('/');
        $initialSessionId = session()->getId();

        // Login and check session regeneration
        $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $newSessionId = session()->getId();
        $this->assertNotEquals($initialSessionId, $newSessionId);
    }
}
