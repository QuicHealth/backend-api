<?php

namespace App\Http\Requests;

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
        if (isset(auth('doctor_api')->user()->id)) {
            $this->userId = auth('doctor_api')->user()->id;
            $this->model = 'doctors';
        } else {
            $this->userId = auth()->user()->id;
            $this->model = 'users';
        }

        return [
            'email' =>  ['sometimes', 'required', 'unique:' . $this->model . ',email,' . $this->userId],
            'phone' => ['sometimes', 'required', 'numeric', 'unique:' . $this->model . ',email,' . $this->userId],
            'dob' => ['sometimes', 'required'],
            'address' => ['sometimes', 'required', 'string'],
            'city' => ['sometimes', 'required', 'string'],
            'gender' => ['sometimes', 'required', 'string'],
            'emergency_number' => ['sometimes', 'required'],
        ];
    }
}