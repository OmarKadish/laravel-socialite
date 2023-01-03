<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
{
    public function index()
    {
        return view('auth.login');
    }

    public function registration()
    {
        return view('auth.register');
    }

    public function loginWithGithub()
    {
        return Socialite::driver('github')->redirect();
    }

    public function githubCallback()
    {
        $githubUser = Socialite::driver('github')->user();

        $user = User::updateOrCreate([
            'email' => $githubUser->email,
        ], [
            'github_id' => $githubUser->id,
            'name' => $githubUser->name,
            'password' => Hash::make(Str::random(24)),
//            'github_token' => $githubUser->token,
//        'github_refresh_token' => $githubUser->refreshToken,
        ]);

        event(new Registered($user));
        Auth::login($user);
        return redirect('/dashboard');
    }

    public function loginWithGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function googleCallback()
    {
        $googleUser = Socialite::driver('google')->user();

        $user = User::updateOrCreate([
            'email' => $googleUser->email,
        ], [
            'google_id' => $googleUser->id,
            'name' => $googleUser->name,
            'password' => Hash::make(Str::random(24)),
//            'github_token' => $githubUser->token,
//        'github_refresh_token' => $githubUser->refreshToken,
        ]);

        event(new Registered($user));
        Auth::login($user);
        return redirect('/dashboard');
    }
    public function dashboard()
    {
        if(Auth::check()){
            return view('dashboard');
        }

        return redirect("login")->withSuccess('You are not allowed to access');
    }

    public function signOut() {
        Session::flush();
        Auth::logout();

        return view('welcome');
    }
}
