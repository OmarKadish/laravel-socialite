<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
//    return Inertia::render('Welcome', [
//        'canLogin' => Route::has('login'),
//        'canRegister' => Route::has('register'),
//        'laravelVersion' => Application::VERSION,
//        'phpVersion' => PHP_VERSION,
//    ]);
    if (Auth::check()){
        return Redirect::to('/dashboard');
    }
    return view('welcome');
});

Route::get('login', [AuthController::class, 'index'])->name('login');
Route::get('register', [AuthController::class, 'registration'])->name('register');
Route::post('register', [RegisteredUserController::class, 'store']);
Route::post('login', [AuthenticatedSessionController::class, 'store']);

//social media
Route::get('github_signin', [AuthController::class, 'loginWithGithub'])->name('github_login');
Route::get('auth/github/callback',  [AuthController::class, 'githubCallback']);
Route::get('google_signin', [AuthController::class, 'loginWithGoogle'])->name('google_login');
Route::get('auth/google/callback',  [AuthController::class, 'googleCallback']);

Route::post('logout', [AuthController::class, 'signOut'])->name('logout');

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
