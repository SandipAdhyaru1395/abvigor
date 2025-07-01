<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterFormRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'dealer_name' => 'required',
            'gst_no' => 'required',
            'full_name' => 'required',
            'email' => 'required|email',
            'address' => 'required',
            'city' => 'required',
            'pincode' => 'required|numeric|digits:6',
            'state' => 'required',
            'phone' => 'required|unique:users|numeric|digits:10',
        ];
    }

    public function messages(): array
    {
        return [
            'dealer_name.required' => 'Please enter dealer name',
            'gst_no.required' => 'Please enter GST No',
            'full_name.required' => 'Please enter full name',
            'email.required' => 'Please enter email',
            'email.email' => 'Please enter valid email',
            'address.required' => 'Please enter address',
            'city.required' => 'Please enter city',
            'pincode.required' => 'Please enter pincode',
            'state.required' => 'Please enter state',
            'phone.required' => 'Please enter phone'
        ];
    }
}
