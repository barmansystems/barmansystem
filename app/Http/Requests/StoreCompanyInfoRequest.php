<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCompanyInfoRequest extends FormRequest
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
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required',
            'economic_number' => 'required',
            'national_number' => 'required',
            'national_id' => 'required',
            'address' => 'required',
            'zip_code' => 'required',
            'phone_number' => 'required',
            'fax_number' => 'required',
            'mobile_number' => 'required',
            'bank_account_number' => 'required',
            'shaba_number' => 'required',
            'account_user_name' => 'required',
            'account_user_password' => 'required',
        ];
    }
}
