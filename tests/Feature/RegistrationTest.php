<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function guests_can_view_registration_page()
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page->component('Auth/Register'));
    }

    #[Test]
    public function authenticated_users_cannot_view_registration_page()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/register');

        $response->assertRedirect('/dashboard');
    }

    #[Test]
    public function users_can_register_with_valid_data()
    {
        $response = $this->post('/register', [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
        ]);

        $response->assertRedirect('/dashboard');
        $response->assertSessionHas('message', 'Registration successful! Welcome to DDO.');
        
        $this->assertDatabaseHas('users', [
            'name' => 'John Doe',
            'email' => 'john@example.com',
        ]);

        $user = User::where('email', 'john@example.com')->first();
        $this->assertAuthenticatedAs($user);
        $this->assertTrue(Hash::check('Password123!', $user->password));
    }

    #[Test]
    public function registration_requires_name()
    {
        $response = $this->post('/register', [
            'email' => 'john@example.com',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
        ]);

        $response->assertSessionHasErrors(['name']);
        $this->assertGuest();
        $this->assertDatabaseCount('users', 0);
    }

    #[Test]
    public function registration_requires_email()
    {
        $response = $this->post('/register', [
            'name' => 'John Doe',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
        ]);

        $response->assertSessionHasErrors(['email']);
        $this->assertGuest();
        $this->assertDatabaseCount('users', 0);
    }

    #[Test]
    public function registration_requires_valid_email_format()
    {
        $response = $this->post('/register', [
            'name' => 'John Doe',
            'email' => 'invalid-email',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
        ]);

        $response->assertSessionHasErrors(['email']);
        $this->assertGuest();
        $this->assertDatabaseCount('users', 0);
    }

    #[Test]
    public function registration_requires_unique_email()
    {
        User::factory()->create(['email' => 'john@example.com']);

        $response = $this->post('/register', [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
        ]);

        $response->assertSessionHasErrors(['email']);
        $this->assertGuest();
        $this->assertDatabaseCount('users', 1);
    }

    #[Test]
    public function registration_requires_password()
    {
        $response = $this->post('/register', [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password_confirmation' => 'Password123!',
        ]);

        $response->assertSessionHasErrors(['password']);
        $this->assertGuest();
        $this->assertDatabaseCount('users', 0);
    }

    #[Test]
    public function registration_requires_password_confirmation()
    {
        $response = $this->post('/register', [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'Password123!',
        ]);

        $response->assertSessionHasErrors(['password']);
        $this->assertGuest();
        $this->assertDatabaseCount('users', 0);
    }

    #[Test]
    public function registration_requires_matching_password_confirmation()
    {
        $response = $this->post('/register', [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'Password123!',
            'password_confirmation' => 'DifferentPassword!',
        ]);

        $response->assertSessionHasErrors(['password']);
        $this->assertGuest();
        $this->assertDatabaseCount('users', 0);
    }

    #[Test]
    public function name_cannot_exceed_255_characters()
    {
        $longName = str_repeat('a', 256);

        $response = $this->post('/register', [
            'name' => $longName,
            'email' => 'john@example.com',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
        ]);

        $response->assertSessionHasErrors(['name']);
        $this->assertGuest();
        $this->assertDatabaseCount('users', 0);
    }

    #[Test]
    public function email_cannot_exceed_255_characters()
    {
        $longEmail = str_repeat('a', 250) . '@example.com';

        $response = $this->post('/register', [
            'name' => 'John Doe',
            'email' => $longEmail,
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
        ]);

        $response->assertSessionHasErrors(['email']);
        $this->assertGuest();
        $this->assertDatabaseCount('users', 0);
    }

    #[Test]
    public function password_must_meet_minimum_requirements()
    {
        $response = $this->post('/register', [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'weak',
            'password_confirmation' => 'weak',
        ]);

        $response->assertSessionHasErrors(['password']);
        $this->assertGuest();
        $this->assertDatabaseCount('users', 0);
    }
}
