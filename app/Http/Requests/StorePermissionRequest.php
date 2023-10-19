<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class StorePermissionRequest extends FormRequest
{
    public function authorize() {
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
            'name' => 'required|string|max:90',
            'permission_for' => 'required|string|max:90',
        ];
    }
 
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json(['success' => false,'message' => $validator->errors()->first()
        ],200));
    }
}
