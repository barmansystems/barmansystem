<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePurchaseRequest extends FormRequest
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
            'count' => 'required',
            'status' => 'required|in:pending_purchase,purchase_done',
        ];
    }

    public function messages()
    {
        return [
            'count.required' => 'تعداد را وارد کنید',
            'status.required' => 'وضعیت را انتخاب کنید.',
        ];
    }
}
