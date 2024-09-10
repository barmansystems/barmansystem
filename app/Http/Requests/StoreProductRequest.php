<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
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
            'title' => 'required',
            'code' => 'required|unique:products,code',
            'sku' => 'required|unique:products,sku',
            'category' => 'required',
            'system_price' => 'required',
            'partner_price_tehran' => 'required',
            'partner_price_other' => 'required',
            'single_price' => 'required',
        ];
    }
}
