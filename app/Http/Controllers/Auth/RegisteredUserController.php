<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use App\Models\Client;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class RegisteredUserController extends Controller
{
    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function register(RegisterRequest $request): \Illuminate\Http\JsonResponse
    {
        try {
            $user = User::create([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'phone' => $request->phone,
                'role' => $request->role,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            event(new Registered($user));

            Auth::login($user);

            $messages = [trans('messages.user_created')];


            if ($user->role === 'client') {
                Client::create([
                    'user_id' => $user->id,
                    'demands_history' => [],
                ]);
                $messages[] = trans('messages.client_created_successfully');
            }

            return response()->json([
                'messages' => $messages,
                'user' => $user,
            ], \Symfony\Component\HttpFoundation\Response::HTTP_OK);
        } catch (\Exception $e) {
            Log::error('User registration failed: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json(['error' => 'User creation failed. Please try again later.'], \Symfony\Component\HttpFoundation\Response::HTTP_BAD_REQUEST);
        }
    }
}
