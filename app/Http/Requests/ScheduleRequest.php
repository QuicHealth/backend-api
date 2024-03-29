<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ScheduleRequest extends FormRequest
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
            'day' => ['required'],
            'availablity' => ['sometimes', 'boolean'],
            'date' => ['sometimes'],
            'time_slots' => ['required'],
            "time_slots.*.start"  => "date_format:H:i|distinct",
            "time_slots.*.end"  => "date_format:H:i|distinct",
            "time_slots.*.selected"  => "required",
            "time_slots.*.status"  => "required",
            "time_slots.*.availablity"  => "sometimes|boolean",
        ];
    }
}
