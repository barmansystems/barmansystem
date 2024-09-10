<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePaymentRequest extends FormRequest
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
            'type' => 'required',
            'amount' => 'required',
            'for' => 'required',
            'site_name' => 'required_if:is_online_payment,true',
            'to' => 'nullable',
            'from' => 'nullable',
        ];
    }

    public function messages()
    {
        return [
            'type.required' => 'وارد کردن نوع پرداخت الزامی است.',
            'amount.required' => 'وارد کردن مبلغ الزامی است.',
            'amount_words.required' => 'مبلغ به حروف را وارد کنید .',
            'for.required' => 'وارد کردن بابت الزامی است.',
            'site_name.required_if' => 'وارد کردن نام سایت الزامی است اگر پرداخت آنلاین باشد.'
        ];
    }

    protected function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if (!$this->filled('to') && !$this->filled('from')) {
                $validator->errors()->add('to', 'این فیلد الزامی است');
                $validator->errors()->add('from', 'این فیلد الزامی است');
            }
        });
    }
}
