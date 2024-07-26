<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules;


class RegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'first_name' => 'required|string|max:' . config('constants.string_firstname'),
            'last_name' => 'required|string|max:' . config('constants.string_lastname'),
            'phone' => 'required|string|max:' . config('constants.string_phone'),
            'role' => ['required', 'string', 'max:' . config('constants.string_max'), Rule::in(['client', 'freelancer'])],
            'email' => 'required|string|lowercase|email|max:' . config('constants.string_max') . '|unique:' . \App\Models\User::class,
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ];
    }
}
