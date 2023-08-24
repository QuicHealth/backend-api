<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class SettingsRequest extends FormRequest
{
    protected $userId;
    protected $model;

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
        // if (isset(auth('doctor_api')->user()->id)) {
        if (auth('doctor_api')->check()) {
            $this->userId =  auth('doctor_api')->user()->id;
            $this->model = 'doctors';
        } else {
            $this->userId =  auth()->user()->id;
            $this->model = 'users';
        }

        return [
            'email' => ['sometimes', 'email', Rule::unique($this->model)->ignore($this->userId)],
            'phone' => ['sometimes', 'numeric', Rule::unique($this->model)->ignore($this->userId)],
            'dob' => ['sometimes'],
            'address' => ['sometimes', 'string'],
            'city' => ['sometimes', 'string'],
            'gender' => ['sometimes', 'string'],
            'emergency_number' => ['sometimes'],
        ];
    }
}
