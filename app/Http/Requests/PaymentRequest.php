<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PaymentRequest extends FormRequest
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
            'amount' => 'required',
            'charge_response_message' => 'Required',
            'charged_amount' => 'required',
            'currency' => 'required',
            'status' => 'required',
            'transaction_id' => 'required',
            'tx_ref' => 'required',
            'customer.user_id' => 'required',
            'customer.appointments_id' => 'required',
            'payment_gateway_type' => 'required'
        ];
    }
}
