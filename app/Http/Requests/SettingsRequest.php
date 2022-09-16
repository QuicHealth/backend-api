<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SettingsRequest extends FormRequest
{
    protected $userId;

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
        $this->userId = auth('doctor_api')->user()->id ?? auth('auth:api')->user()->id;

        return [
            'email' =>  ['required', 'unique:doctors,email,' . $this->userId],
            'phone' => ['required', 'numeric', 'unique:doctors,email,' . $this->userId],
            'dob' => ['required'],
            'address' => ['required', 'string'],
            'city' => ['required', 'string'],
            'gender' => ['required', 'string'],
            'emergency_number' => ['required'],
        ];
    }
}