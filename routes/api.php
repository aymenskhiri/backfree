<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FreelancerProfileController;


//freelancer
Route::apiResource('freelancer-profiles', FreelancerProfileController::class);
Route::get('/freelancer-profiles/{freelancerProfile}', [FreelancerProfileController::class, 'show']);
Route::put('/freelancer-profiles/{freelancerProfile}', [FreelancerProfileController::class, 'update']);
Route::delete('/freelancer-profiles/{freelancerProfile}', [FreelancerProfileController::class, 'destroy']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/posts', [\App\Http\Controllers\PostController::class, 'index']);
Route::post('/posts', [\App\Http\Controllers\PostController::class, 'store']);
Route::get('/posts/{post}', [\App\Http\Controllers\PostController::class, 'show']);
Route::put('/posts/{post}', [\App\Http\Controllers\PostController::class, 'update']);
Route::delete('/posts/{post}', [\App\Http\Controllers\PostController::class, 'destroy']);


//auth
Route::post('/register', [\App\Http\Controllers\Auth\RegisteredUserController::class, 'register'])
    ->middleware('guest')
    ->name('register');

Route::post('/login', [\App\Http\Controllers\Auth\AuthenticatedSessionController::class, 'login'])
    ->middleware('guest')
    ->name('login');

Route::post('/forgot-password', [\App\Http\Controllers\Auth\PasswordResetLinkController::class, 'store'])
    ->middleware('guest')
    ->name('password.email');

Route::post('/reset-password', [\App\Http\Controllers\Auth\NewPasswordController::class, 'store'])
    ->middleware('guest')
    ->name('password.store');

Route::middleware(['auth:sanctum', 'verified'])->group(function () {
    Route::post('/logout', [\App\Http\Controllers\Auth\AuthenticatedSessionController::class, 'logout'])
        ->name('logout');
});

