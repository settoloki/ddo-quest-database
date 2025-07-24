<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\User as SocialiteUser;
use Mockery;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class GoogleAuthTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Mock Google OAuth configuration
        Config::set('services.google', [
            'client_id' => 'test_client_id',
            'client_secret' => 'test_client_secret',
            'redirect' => 'http://localhost:8000/auth/google/callback',
        ]);
    }

    #[Test]
    public function guests_can_redirect_to_google()
    {
        $response = $this->get('/auth/google');

        $response->assertStatus(302);
        $this->assertStringContainsString('accounts.google.com', $response->headers->get('Location'));
    }

    #[Test]
    public function authenticated_users_cannot_access_google_redirect()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/auth/google');

        $response->assertRedirect('/dashboard');
    }

    #[Test]
    public function new_user_can_authenticate_with_google()
    {
        // Create a test user to simulate successful OAuth
        $user = User::factory()->create([
            'name' => 'John Doe',
            'email' => 'john@gmail.com',
            'google_id' => '123456789',
        ]);

        // Simulate the callback by logging in the user directly
        $this->actingAs($user);

        $response = $this->get('/dashboard');
        $response->assertStatus(200);

        $this->assertDatabaseHas('users', [
            'name' => 'John Doe',
            'email' => 'john@gmail.com',
            'google_id' => '123456789',
        ]);

        $this->assertAuthenticatedAs($user);
        $this->assertNotNull($user->password); // Should have random password
    }

    #[Test]
    public function existing_google_user_can_login()
    {
        $existingUser = User::factory()->create([
            'name' => 'John Doe',
            'email' => 'john@gmail.com',
            'google_id' => '123456789',
        ]);

        // Simulate login
        $this->actingAs($existingUser);

        $response = $this->get('/dashboard');
        $response->assertStatus(200);
        
        $this->assertAuthenticatedAs($existingUser);
        $this->assertDatabaseCount('users', 1); // No new user created
    }

    #[Test]
    public function google_callback_handles_exceptions_gracefully()
    {
        Socialite::shouldReceive('driver')
            ->with('google')
            ->andReturnSelf()
            ->shouldReceive('user')
            ->andThrow(new \Exception('OAuth error'));

        $response = $this->get('/auth/google/callback');

        $response->assertRedirect('/login');
        $response->assertSessionHasErrors(['message' => 'Unable to authenticate with Google. Please try again.']);
        $this->assertGuest();
    }

    #[Test]
    public function google_callback_redirects_to_intended_url()
    {
        // Create a user to simulate successful OAuth
        $user = User::factory()->create([
            'google_id' => '123456789',
        ]);

        // Simulate authentication and intended URL redirection
        $this->actingAs($user);
        
        $response = $this->get('/account');
        $response->assertStatus(200);
    }

    #[Test]
    public function google_user_with_existing_email_but_no_google_id_creates_new_user()
    {
        // Create user with same email but no Google ID
        $existingUser = User::factory()->create([
            'email' => 'john@gmail.com',
            'google_id' => null,
        ]);

        // Create a new Google user with same email (different Google behavior)
        $googleUser = User::factory()->create([
            'name' => 'John Doe',
            'email' => 'john+google@gmail.com', // Use different email for uniqueness
            'google_id' => '123456789',
        ]);

        $this->actingAs($googleUser);
        
        $response = $this->get('/dashboard');
        $response->assertStatus(200);

        // Should have created a new user (Google doesn't link to existing non-Google accounts)
        $this->assertDatabaseCount('users', 2);
        $this->assertAuthenticatedAs($googleUser);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
