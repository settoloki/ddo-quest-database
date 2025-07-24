<?php

namespace Tests\Unit;

use App\Http\Controllers\Auth\LoginController;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class LoginControllerTest extends TestCase
{
    use RefreshDatabase;

    protected LoginController $controller;

    protected function setUp(): void
    {
        parent::setUp();
        $this->controller = new LoginController();
    }

    #[Test]
    public function create_returns_login_view()
    {
        $response = $this->controller->create();

        $this->assertInstanceOf(\Inertia\Response::class, $response);
    }

    #[Test]
    public function store_validates_required_fields()
    {
        $request = Request::create('/login', 'POST', []);

        $this->expectException(\Illuminate\Validation\ValidationException::class);

        $this->controller->store($request);
    }

    #[Test]
    public function store_validates_email_format()
    {
        $request = Request::create('/login', 'POST', [
            'email' => 'invalid-email',
            'password' => 'password',
        ]);

        $this->expectException(\Illuminate\Validation\ValidationException::class);

        $this->controller->store($request);
    }

    #[Test]
    public function store_authenticates_valid_user()
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
        ]);

        $request = Request::create('/login', 'POST', [
            'email' => 'test@example.com',
            'password' => 'password123',
        ]);

        $request->setLaravelSession(app('session.store'));

        $response = $this->controller->store($request);

        $this->assertTrue(Auth::check());
        $this->assertEquals($user->id, Auth::id());
        $this->assertEquals(302, $response->getStatusCode());
    }

    #[Test]
    public function store_fails_with_invalid_credentials()
    {
        User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
        ]);

        $request = Request::create('/login', 'POST', [
            'email' => 'test@example.com',
            'password' => 'wrongpassword',
        ]);

        $request->setLaravelSession(app('session.store'));

        $response = $this->controller->store($request);

        $this->assertFalse(Auth::check());
        $this->assertEquals(302, $response->getStatusCode());
    }

    #[Test]
    public function store_handles_remember_me()
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
        ]);

        $request = Request::create('/login', 'POST', [
            'email' => 'test@example.com',
            'password' => 'password123',
            'remember' => '1',
        ]);

        $request->setLaravelSession(app('session.store'));

        $response = $this->controller->store($request);

        $this->assertTrue(Auth::check());
        $this->assertNotNull($user->fresh()->remember_token);
    }
}
