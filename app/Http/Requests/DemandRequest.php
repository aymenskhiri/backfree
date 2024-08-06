<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class DemandRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'status' => [
                'required',
                'string',
                Rule::in(['Done', 'Progressing', 'On Hold']),
            ],
            'approuval' => [
                'string',
                Rule::in(['Accepted','Rejected', 'On Hold']),
            ],
            'service_date' => 'required|date',
            'description' => 'required|string|max:' . config('constants.string_max'),
            'post_id' => 'required|exists:posts,id',
            'freelancer_id' => 'required|exists:freelancer_profiles,id',
            'client_id' => 'required|exists:clients,id',
            'begin_hour' => 'required|date_format:H:i',
        ];
    }
}
