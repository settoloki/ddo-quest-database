<?php

namespace Tests\Unit;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class UserModelTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function user_can_be_created_with_required_fields()
    {
        $user = User::create([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => Hash::make('password123'),
        ]);

        $this->assertDatabaseHas('users', [
            'name' => 'John Doe',
            'email' => 'john@example.com',
        ]);

        $this->assertEquals('John Doe', $user->name);
        $this->assertEquals('john@example.com', $user->email);
        $this->assertTrue(Hash::check('password123', $user->password));
    }

    #[Test]
    public function user_can_be_created_with_google_id()
    {
        $user = User::create([
            'name' => 'Jane Doe',
            'email' => 'jane@gmail.com',
            'password' => Hash::make('randompassword'),
            'google_id' => '123456789',
        ]);

        $this->assertDatabaseHas('users', [
            'name' => 'Jane Doe',
            'email' => 'jane@gmail.com',
            'google_id' => '123456789',
        ]);

        $this->assertEquals('123456789', $user->google_id);
    }

    #[Test]
    public function password_is_hidden_in_array_conversion()
    {
        $user = User::factory()->create([
            'password' => Hash::make('secret123'),
        ]);

        $userArray = $user->toArray();

        $this->assertArrayNotHasKey('password', $userArray);
    }

    #[Test]
    public function remember_token_is_hidden_in_array_conversion()
    {
        $user = User::factory()->create([
            'remember_token' => 'test_token',
        ]);

        $userArray = $user->toArray();

        $this->assertArrayNotHasKey('remember_token', $userArray);
    }

    #[Test]
    public function email_verified_at_is_cast_to_datetime()
    {
        $user = User::factory()->create([
            'email_verified_at' => now(),
        ]);

        $this->assertInstanceOf(\Illuminate\Support\Carbon::class, $user->email_verified_at);
    }

    #[Test]
    public function password_is_automatically_hashed()
    {
        $user = User::factory()->create([
            'password' => 'plaintext',
        ]);

        $this->assertNotEquals('plaintext', $user->password);
        $this->assertTrue(Hash::check('plaintext', $user->password));
    }

    #[Test]
    public function user_has_factory()
    {
        $user = User::factory()->create();

        $this->assertNotNull($user->name);
        $this->assertNotNull($user->email);
        $this->assertNotNull($user->password);
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
        ]);
    }

    #[Test]
    public function user_can_be_found_by_google_id()
    {
        $user = User::factory()->create([
            'google_id' => '987654321',
        ]);

        $foundUser = User::where('google_id', '987654321')->first();

        $this->assertEquals($user->id, $foundUser->id);
        $this->assertEquals('987654321', $foundUser->google_id);
    }

    #[Test]
    public function mass_assignment_protection_works()
    {
        $user = User::create([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => Hash::make('password123'),
            'google_id' => '123456789',
            'id' => 999, // This should be ignored
        ]);

        $this->assertNotEquals(999, $user->id);
        $this->assertEquals('John Doe', $user->name);
        $this->assertEquals('john@example.com', $user->email);
        $this->assertEquals('123456789', $user->google_id);
    }

    #[Test]
    public function user_attributes_are_fillable()
    {
        $fillable = (new User())->getFillable();

        $expectedFillable = ['name', 'email', 'password', 'google_id'];

        $this->assertEquals($expectedFillable, $fillable);
    }

    #[Test]
    public function user_hidden_attributes_are_correct()
    {
        $hidden = (new User())->getHidden();

        $expectedHidden = ['password', 'remember_token'];

        $this->assertEquals($expectedHidden, $hidden);
    }
}
