<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ServiceDemandRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'post_id' => 'required|exists:posts,id',
            'freelancer_id' => 'required|exists:freelancer_profiles,id',
            'client_id' => 'required|exists:users,id',
            'additional_field' => 'required|string|max:' . config('constants.string_max'),
            'status' => ['required', 'string', 'max:' . config('constants.string_max'), Rule::in(['done','progressing','rejected','hold'])],

        ];
    }
}
