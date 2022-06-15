<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class UpdatePatientRequest extends FormRequest
{
    /**
     * Indicates if the validator should stop on the first rule failure.
     *
     * @var bool
     */
    protected $stopOnFirstFailure = true;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'firstname' => ['required', 'string'],
            'lastname' => ['required', 'string'],
            'email' => ['required', 'string', 'email'],
            'gender' => ['required', 'string'],
            'phone' => ['required', 'string'],
            'dob' => ['required', Password::defaults()],
        ];
    }
}