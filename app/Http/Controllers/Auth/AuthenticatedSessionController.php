<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Log;

class AuthenticatedSessionController extends Controller
{

    /**
     * @throws ValidationException
     */
    public function store(LoginRequest $request): Response
    {
        try {
            $request->authenticate();

            $request->session()->regenerate();

            return response('User logged in successfully', 200);
        } catch (\Exception $e) {
            Log::error('Login attempt failed: ' . $e->getMessage());
            return response('Login failed. Please try again later.', 500);
        }
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): Response
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return response()->noContent();
    }
}
