<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class AuthenticatedSessionController extends Controller
{
    /**
     * @throws ValidationException
     */
    public function login(LoginRequest $request): \Illuminate\Http\JsonResponse
    {
        try {
            $this->authenticate($request);

            $user = $request->user();
            $token = $user->createToken('auth_token')->plainTextToken;

            // Retrieve related data
            $client = $user->client;
            $freelancerProfile = $user->freelancerProfile;

            return response()->json([
                'message' => trans('messages.login_success'),
                'token' => $token,
                'user' => $user,
                'client_id' => $client ? $client->id : null,
                'freelancer_id' => $freelancerProfile ? $freelancerProfile->id : null,
            ], \Symfony\Component\HttpFoundation\Response::HTTP_OK);
        } catch (ValidationException $e) {
            Log::error('Login attempt failed: ' . $e->getMessage());
            return response()->json(['error' => trans('messages.invalid_credentials')], \Symfony\Component\HttpFoundation\Response::HTTP_UNAUTHORIZED);
        } catch (\Exception $e) {
            Log::error('Login attempt failed: ' . $e->getMessage());
            return response()->json(['error' => trans('messages.login_failed')], \Symfony\Component\HttpFoundation\Response::HTTP_BAD_REQUEST);
        }
    }


    /**
     * Attempt to authenticate the request's credentials.
     *
     * @param LoginRequest $request
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function authenticate(LoginRequest $request): void
    {
        $this->ensureIsNotRateLimited($request);

        if (! Auth::attempt($request->only('email', 'password'), $request->boolean('remember'))) {
            RateLimiter::hit($this->throttleKey($request));

            throw ValidationException::withMessages([
                'email' => __('auth.failed'),
            ]);
        }

        RateLimiter::clear($this->throttleKey($request));
    }


    protected function ensureIsNotRateLimited(LoginRequest $request): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey($request), 5)) {
            return;
        }

        $seconds = RateLimiter::availableIn($this->throttleKey($request));

        throw ValidationException::withMessages([
            'email' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }


    protected function throttleKey(LoginRequest $request): string
    {
        return Str::transliterate(Str::lower($request->input('email')).'|'.$request->ip());
    }



    public function logout(Request $request): Response
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return response()->noContent();
    }
}
