<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePriceRequestRequest extends FormRequest
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
            'max_send_time' => 'required',
            'products' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'products.required' => 'وارد کردن کالاهای مربوطه الزامی است'
        ];
    }
}
