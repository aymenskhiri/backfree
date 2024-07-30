<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class PostRequest extends FormRequest
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
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
                'freelancer_profile_id' => 'required|exists:freelancer_profiles,id',
                'title' => 'required|string|max:' . config('constants.string_max'),
                'description' => 'required|string',
                'image' => 'nullable|image|mimes:jpg,png,jpeg,gif',
        ];
    }
}
