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
            $this->userId = 'doctor_api' . auth('doctor_api')->user()->id;
            $this->model = 'doctors';
        } else {
            $this->userId = 'user_api' . auth()->user()->id;
            $this->model = 'users';
        }

        dd($this->model, $this->userId);

        return [
            'email' => ['sometimes', 'required', 'email', Rule::unique($this->model)->ignore($this->userId)],
            'phone' => ['sometimes', 'required', 'numeric', Rule::unique($this->model)->ignore($this->userId)],
            'dob' => ['sometimes', 'required'],
            'address' => ['sometimes', 'required', 'string'],
            'city' => ['sometimes', 'required', 'string'],
            'gender' => ['sometimes', 'required', 'string'],
            'emergency_number' => ['sometimes', 'required'],
        ];
    }
}