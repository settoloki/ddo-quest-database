<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class GoogleController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $user = Socialite::driver('google')->user();

            $findUser = User::where('google_id', $user->id)->first();

            if ($findUser) {
                Auth::login($findUser);
                return redirect()->intended(route('dashboard'))->with('message', 'Welcome back!');
            }

            $newUser = User::create([
                'name' => $user->name,
                'email' => $user->email,
                'google_id' => $user->id,
                'password' => Hash::make(Str::random(32)) // Random password for Google users
            ]);

            Auth::login($newUser);
            return redirect()->intended(route('dashboard'))->with('message', 'Welcome to DDO!');
            
        } catch (\Exception $e) {
            return redirect()->route('login')->withErrors([
                'message' => 'Unable to authenticate with Google. Please try again.'
            ]);
        }
    }
}

