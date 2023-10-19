<?php

namespace App\Http\Requests\Admin\SubscriptionPlan;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class AddEditPlansRequest extends FormRequest
{
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
            'product' => ['required','exists:products,id'],
            'plan' => ['required','exists:plan_types,id'],
            'medicine' => ['required','exists:medicine_varients,id'],
            'plan_title' => ['required'],
            'image' => ['required_if:id,=,0','mimes:png,jpeg,jpg','max:204800'],
            'strength' => 'required|numeric',
            'is_active' => 'required|numeric|in:0,1',
            'quantity1' => 'required',
            'quantity2' => 'required',
            'price1' => 'required',
            'price2' => 'required',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json(['success' => false,'message' => $validator->errors()->first()
        ],200));
    }
}
