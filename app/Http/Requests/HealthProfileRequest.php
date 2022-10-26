<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class HealthProfileRequest extends FormRequest
{
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
            'blood_group'     => ['sometimes', 'required', 'string'],
            'genotype'      => ['sometimes', 'required', 'string'],
            'martial_status'        => ['sometimes', 'required', 'string'],
            'medication'           => ['sometimes', 'required', 'string'],
            'family_medical_history'  => ['sometimes', 'required', 'string'],
            'health_condition'  => ['sometimes', 'required', 'string'],
            'peculiar_cases'  => ['sometimes', 'required', 'string'],
            'allergies'  => ['sometimes', 'required', 'string'],
            'Occupation'  => ['sometimes', 'required', 'string'],
            'past_medical_history'  => ['sometimes', 'required', 'string'],
        ];
    }
}