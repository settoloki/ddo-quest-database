<?php

namespace Tests\Unit;

use Database\Factories\UserFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class UserFactoryTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function user_factory_creates_valid_users()
    {
        $user = UserFactory::new()->create();

        $this->assertNotNull($user->name);
        $this->assertNotNull($user->email);
        $this->assertNotNull($user->password);
        $this->assertNotNull($user->email_verified_at); // Factory creates verified users by default
        $this->assertNull($user->google_id);
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'email' => $user->email,
        ]);
    }

    #[Test]
    public function user_factory_can_override_attributes()
    {
        $user = UserFactory::new()->create([
            'name' => 'Custom Name',
            'email' => 'custom@example.com',
        ]);

        $this->assertEquals('Custom Name', $user->name);
        $this->assertEquals('custom@example.com', $user->email);
    }

    #[Test]
    public function user_factory_generates_unique_emails()
    {
        $user1 = UserFactory::new()->create();
        $user2 = UserFactory::new()->create();

        $this->assertNotEquals($user1->email, $user2->email);
    }

    #[Test]
    public function user_factory_can_create_multiple_users()
    {
        $users = UserFactory::new()->count(5)->create();

        $this->assertCount(5, $users);
        $this->assertDatabaseCount('users', 5);
    }

    #[Test]
    public function user_factory_can_make_users_without_persisting()
    {
        $user = UserFactory::new()->make();

        $this->assertNotNull($user->name);
        $this->assertNotNull($user->email);
        $this->assertNotNull($user->password);
        $this->assertNull($user->id);
        $this->assertDatabaseCount('users', 0);
    }

    #[Test]
    public function user_factory_generates_valid_email_format()
    {
        $user = UserFactory::new()->create();

        $this->assertMatchesRegularExpression('/^[^\s@]+@[^\s@]+\.[^\s@]+$/', $user->email);
    }

    #[Test]
    public function user_factory_can_create_verified_users()
    {
        $user = UserFactory::new()->create([
            'email_verified_at' => now(),
        ]);

        $this->assertNotNull($user->email_verified_at);
        $this->assertInstanceOf(\Illuminate\Support\Carbon::class, $user->email_verified_at);
    }

    #[Test]
    public function user_factory_can_create_google_users()
    {
        $user = UserFactory::new()->create([
            'google_id' => '123456789',
        ]);

        $this->assertEquals('123456789', $user->google_id);
    }

    #[Test]
    public function user_factory_passwords_are_hashed()
    {
        $user = UserFactory::new()->create();

        $this->assertNotEquals('password', $user->password);
        $this->assertTrue(\Illuminate\Support\Facades\Hash::check('password', $user->password));
    }

    #[Test]
    public function user_factory_creates_realistic_names()
    {
        $user = UserFactory::new()->create();

        // Check that name contains at least two words (first and last name)
        $nameParts = explode(' ', $user->name);
        $this->assertGreaterThanOrEqual(2, count($nameParts));
        
        // Check that each part is not empty
        foreach ($nameParts as $part) {
            $this->assertNotEmpty(trim($part));
        }
    }
}
