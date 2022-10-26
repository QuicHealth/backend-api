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
            'blood_group'     => ['sometimes', 'required'],
            'genotype'      => ['sometimes', 'required'],
            'martial_status'        => ['sometimes', 'required'],
            'medication'           => ['sometimes', 'required'],
            'family_medical_history'  => ['sometimes', 'required'],
            'health_condition'  => ['sometimes', 'required'],
            'peculiar_cases'  => ['sometimes', 'required'],
            'allergies'  => ['sometimes', 'required'],
            'Occupation'  => ['sometimes', 'required'],
            'past_medical_history'  => ['sometimes', 'required'],
        ];
    }
}