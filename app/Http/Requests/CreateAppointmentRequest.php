<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateAppointmentRequest extends FormRequest
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
            'doctor_id' => ['required'],
            'day' => ['required', 'string'],
            'date' => ['required', 'date_format:d-m-Y', 'after:today'],
            "time_slots"  => ['required', 'array'],
            "time_slots.start"  => ['required', 'date_format:H:i'],
            "time_slots.end"  => ['required', 'date_format:H:i'],
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'date.after' => 'Sorry, this date is in the past',
        ];
    }
}
