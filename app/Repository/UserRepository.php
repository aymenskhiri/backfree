<?php

namespace App\Repository;

class UserRepository
{
    public function prepareUserData($request): array
    {
        return [
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'phone' => $request->phone,
            'role' => $request->role,
            'email' => $request->email,
            'password' => \Illuminate\Support\Facades\Hash::make($request->password),
        ];
    }

    public function prepareClientData($user): array
    {
        return [
            'user_id' => $user->id,
            'demands_history' => [],
        ];
    }
}
