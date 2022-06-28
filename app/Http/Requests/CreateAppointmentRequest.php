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
            'day_id' => ['required'],
            "time_slots.start"  => ['required', 'date_format:H:i'],
            "time_slots.end"  => ['required', 'date_format:H:i'],
        ];
    }
}