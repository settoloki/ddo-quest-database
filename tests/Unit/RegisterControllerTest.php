<?php

namespace Tests\Unit;

use App\Http\Controllers\Auth\RegisterController;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class RegisterControllerTest extends TestCase
{
    use RefreshDatabase;

    protected RegisterController $controller;

    protected function setUp(): void
    {
        parent::setUp();
        $this->controller = new RegisterController();
    }

    #[Test]
    public function create_returns_register_view()
    {
        $response = $this->controller->create();

        $this->assertInstanceOf(\Inertia\Response::class, $response);
    }

    #[Test]
    public function store_validates_required_fields()
    {
        $request = Request::create('/register', 'POST', []);

        $this->expectException(\Illuminate\Validation\ValidationException::class);

        $this->controller->store($request);
    }

    #[Test]
    public function store_validates_email_uniqueness()
    {
        User::factory()->create(['email' => 'test@example.com']);

        $request = Request::create('/register', 'POST', [
            'name' => 'John Doe',
            'email' => 'test@example.com',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
        ]);

        $this->expectException(\Illuminate\Validation\ValidationException::class);

        $this->controller->store($request);
    }

    #[Test]
    public function store_validates_password_confirmation()
    {
        $request = Request::create('/register', 'POST', [
            'name' => 'John Doe',
            'email' => 'test@example.com',
            'password' => 'Password123!',
            'password_confirmation' => 'DifferentPassword!',
        ]);

        $this->expectException(\Illuminate\Validation\ValidationException::class);

        $this->controller->store($request);
    }

    #[Test]
    public function store_creates_user_with_valid_data()
    {
        $request = Request::create('/register', 'POST', [
            'name' => 'John Doe',
            'email' => 'test@example.com',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
        ]);

        $request->setLaravelSession(app('session.store'));

        $response = $this->controller->store($request);

        $this->assertDatabaseHas('users', [
            'name' => 'John Doe',
            'email' => 'test@example.com',
        ]);

        $user = User::where('email', 'test@example.com')->first();
        $this->assertTrue(Hash::check('Password123!', $user->password));
        $this->assertTrue(Auth::check());
        $this->assertEquals($user->id, Auth::id());
        $this->assertEquals(302, $response->getStatusCode());
    }

    #[Test]
    public function store_validates_name_max_length()
    {
        $request = Request::create('/register', 'POST', [
            'name' => str_repeat('a', 256),
            'email' => 'test@example.com',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
        ]);

        $this->expectException(\Illuminate\Validation\ValidationException::class);

        $this->controller->store($request);
    }

    #[Test]
    public function store_validates_email_max_length()
    {
        $request = Request::create('/register', 'POST', [
            'name' => 'John Doe',
            'email' => str_repeat('a', 250) . '@example.com',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
        ]);

        $this->expectException(\Illuminate\Validation\ValidationException::class);

        $this->controller->store($request);
    }

    #[Test]
    public function store_validates_email_format()
    {
        $request = Request::create('/register', 'POST', [
            'name' => 'John Doe',
            'email' => 'invalid-email',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
        ]);

        $this->expectException(\Illuminate\Validation\ValidationException::class);

        $this->controller->store($request);
    }

    #[Test]
    public function store_validates_password_rules()
    {
        $request = Request::create('/register', 'POST', [
            'name' => 'John Doe',
            'email' => 'test@example.com',
            'password' => 'weak',
            'password_confirmation' => 'weak',
        ]);

        $this->expectException(\Illuminate\Validation\ValidationException::class);

        $this->controller->store($request);
    }
}
