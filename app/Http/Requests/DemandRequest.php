<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Date;

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
        $rules = [
            'description' => 'required|string|max:' . config('constants.string_max'),
            'service_date' => [
                'required',
                'date',
                'after_or_equal:today',
                function ($attribute, $value, $fail) {
                    if (\Carbon\Carbon::parse($value)->lt(now()->startOfDay())) {
                        $fail('The ' . $attribute . ' cannot be a date in the past.');
                    }
                },
            ],


            'begin_hour' => [
                'required',
                'date_format:H:i',
                function ($attribute, $value, $fail) {
                    if (Date::parse($value)->lt(now()->addHour())) {
                        $fail('The Start Hour must be one hour from now.');
                    }
                },
            ],
        ];

        if ($this->isMethod('post')) {
            $rules += [
                'status' => [
                    'required',
                    'string',
                    Rule::in(['Done', 'Progressing', 'On Hold']),
                ],
                'approuval' => [
                    'string',
                    Rule::in(['Accepted', 'Rejected', 'On Hold']),
                ],
                'post_id' => 'required|exists:posts,id',
                'freelancer_id' => 'required|exists:freelancer_profiles,id',
                'client_id' => 'required|exists:clients,id',
            ];
        }

        return $rules;
    }

}
