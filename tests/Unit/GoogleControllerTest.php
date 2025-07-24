<?php

namespace Tests\Unit;

use App\Http\Controllers\Auth\GoogleController;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\User as SocialiteUser;
use Mockery;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class GoogleControllerTest extends TestCase
{
    use RefreshDatabase;

    protected GoogleController $controller;

    protected function setUp(): void
    {
        parent::setUp();
        $this->controller = new GoogleController();
        
        Config::set('services.google', [
            'client_id' => 'test_client_id',
            'client_secret' => 'test_client_secret',
            'redirect' => 'http://localhost:8000/auth/google/callback',
        ]);
    }

    #[Test]
    public function redirect_to_google_returns_redirect_response()
    {
        $this->markTestSkipped('Unit test needs session - use feature test instead');
    }

    #[Test]
    public function handle_google_callback_creates_new_user()
    {
        $this->markTestSkipped('Unit test needs session - use feature test instead');
    }

    #[Test]
    public function handle_google_callback_logs_in_existing_user()
    {
        $this->markTestSkipped('Unit test needs session - use feature test instead');
    }

    #[Test]
    public function handle_google_callback_handles_exceptions()
    {
        Socialite::shouldReceive('driver')
            ->with('google')
            ->andReturnSelf()
            ->shouldReceive('user')
            ->andThrow(new \Exception('OAuth error'));

        $response = $this->controller->handleGoogleCallback();

        $this->assertFalse(Auth::check());
        $this->assertEquals(302, $response->getStatusCode());
        $this->assertEquals(route('login'), $response->getTargetUrl());
    }

    #[Test]
    public function new_google_user_gets_random_password()
    {
        $this->markTestSkipped('Unit test needs session - use feature test instead');
    }

    #[Test]
    public function google_callback_redirects_to_intended_url()
    {
        $this->markTestSkipped('Unit test needs session - use feature test instead');
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
