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
Route::get('/posts/freelancer/{id}', [\App\Http\Controllers\PostController::class, 'getPostsByFreelancer']);



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
Route::middleware('api')->get('/csrf-token', function (Request $request) {
    return response()->json(['csrf_token' => csrf_token()]);
});
Route::middleware('api')->post('/logout', [\App\Http\Controllers\Auth\AuthenticatedSessionController::class, 'logout']);



Route::resource('service-demands', \App\Http\Controllers\DemandController::class)->middleware('auth:sanctum');

// Client
Route::post('/clients', [\App\Http\Controllers\ClientController::class, 'store']);
Route::get('/clients/{client}', [\App\Http\Controllers\ClientController::class, 'show']);
Route::put('/clients/{client}', [\App\Http\Controllers\ClientController::class, 'update']);
Route::delete('/clients/{client}', [\App\Http\Controllers\ClientController::class, 'destroy']);








//demands

Route::apiResource('demands', \App\Http\Controllers\DemandController::class);
Route::get('/freelancers/{freelancerId}/demands', [\App\Http\Controllers\DemandController::class, 'getDemandsByFreelancer']);
Route::get('/freelancers/{freelancerId}/demands/approved', [\App\Http\Controllers\DemandController::class, 'getDemandsByFreelancerApprouved']);
Route::get('/clients/{clientId}/demands', [\App\Http\Controllers\DemandController::class, 'getDemandsByClient']);
Route::patch('demands/{id}/status', [\App\Http\Controllers\DemandController::class, 'updateStatus']);
Route::patch('/demands/{id}/approuval', [\App\Http\Controllers\DemandController::class, 'updateApprouval']);


