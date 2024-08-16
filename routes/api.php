<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FreelancerProfileController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\DemandController;
use \App\Http\Controllers\ClientController;

//user
Route::get('/users', [UserController::class, 'index']);
Route::put('/users/{id}', [UserController::class, 'update']);
Route::delete('/users/{id}', [UserController::class, 'destroy']);

//freelancer
Route::apiResource('freelancer-profiles', FreelancerProfileController::class);
Route::get('/freelancer-profiles/{freelancerProfile}', [FreelancerProfileController::class, 'show']);
Route::put('/freelancer-profiles/{freelancerProfile}', [FreelancerProfileController::class, 'update']);
Route::delete('/freelancer-profiles/{freelancerProfile}', [FreelancerProfileController::class, 'destroy']);
Route::get('freelancers/{id}', [FreelancerProfileController::class, 'getFreelancerProfile']);
Route::post('freelancers/{id}/rate', [FreelancerProfileController::class, 'rate']);
Route::get('/freelancers/{id}/has-rated', [FreelancerProfileController::class, 'hasRated']);
Route::get('freelancer-profiles', [FreelancerProfileController::class, 'index']);


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/posts', [PostController::class, 'index']);
Route::post('/posts', [PostController::class, 'store']);
Route::get('/posts/{post}', [PostController::class, 'show']);
Route::put('/posts/{post}', [PostController::class, 'update']);
Route::delete('/posts/{post}', [PostController::class, 'destroy']);
Route::get('/posts/freelancer/{id}', [PostController::class, 'getPostsByFreelancer']);
Route::get('posts/search', [PostController::class, 'search']);
Route::get('/freelancers/{freelancerId}/demands', [DemandController::class, 'index']);




//auth
Route::post('/register', [RegisteredUserController::class, 'register'])
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



Route::resource('service-demands', DemandController::class)->middleware('auth:sanctum');

// Client
Route::post('/clients', [ClientController::class, 'store']);
Route::get('/clients/{client}', [ClientController::class, 'show']);
Route::put('/clients/{client}', [ClientController::class, 'update']);
Route::delete('/clients/{client}', [ClientController::class, 'destroy']);








//demands

Route::apiResource('demands', \App\Http\Controllers\DemandController::class);
Route::get('/freelancers/{freelancerId}/demands', [\App\Http\Controllers\DemandController::class, 'getDemandsByFreelancer']);
Route::get('/freelancers/{freelancerId}/demands/approved', [\App\Http\Controllers\DemandController::class, 'getDemandsByFreelancerApprouved']);
Route::get('/clients/{clientId}/demands', [\App\Http\Controllers\DemandController::class, 'getDemandsByClient']);
Route::patch('demands/{id}/status', [\App\Http\Controllers\DemandController::class, 'updateStatus']);
Route::patch('/demands/{id}/approuval', [\App\Http\Controllers\DemandController::class, 'updateApprouval']);


