<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use App\Models\Client;
use App\Repository\UserRepository;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class RegisteredUserController extends Controller
{
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function register(RegisterRequest $request): \Illuminate\Http\JsonResponse
    {
        try {
            $userData = $this->userRepository->prepareUserData($request);

            $user = User::create($userData);

            event(new Registered($user));

            Auth::login($user);

            $messages = [trans('messages.user_created')];

            if ($user->role === 'client') {
                $clientData = $this->userRepository->prepareClientData($user);

                Client::create($clientData);
                $messages[] = trans('messages.client_created_successfully');
            }

            return response()->json([
                'messages' => $messages,
                'user' => $user,
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            Log::error('User registration failed: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json(['error' => 'User creation failed. Please try again later.'], Response::HTTP_BAD_REQUEST);
        }
    }
}
