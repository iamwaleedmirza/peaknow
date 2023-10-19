<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class QuesAnsRequest extends FormRequest
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
            'que_1' => ['required', Rule::in(que_1)],
            'ans_1' => ['required', Rule::in(que_1_options)],

            'que_2' => ['required', Rule::in(que_2)],
            'ans_2' => ['required'],

            'que_3__1' => ['required', Rule::in(que_3__1)],
            'ans_3__1.*' => ['required', Rule::in(que_3__1_options)],

            'que_3__2' => ['required', Rule::in(que_3__2)],

            'que_4__1' => ['required', Rule::in(que_4__1)],
            'ans_4__1.*' => ['required', Rule::in(que_4__1_options)],

            'que_4__2' => ['required', Rule::in(que_4__2)],

            'que_5' => ['required', Rule::in(que_5)],
            'ans_5.*' => ['required', Rule::in(que_5_options)],

            'que_6__1' => ['required', Rule::in(que_6__1)],
            'ans_6__1.*' => ['required', Rule::in(que_6__1_options)],

            'que_6__2' => ['required', Rule::in(que_6__2)],

            'que_7__1' => ['required', Rule::in(que_7__1)],
            'ans_7__1.*' => ['required', Rule::in(que_7__1_options)],

            'que_7__2' => ['required', Rule::in(que_7__2)],

            'que_8' => ['required', Rule::in(que_8)],
            'ans_8.*' => ['required', Rule::in(que_8_options)],

            'que_9' => ['required', Rule::in(que_9)],
            'ans_9' => ['required', 'in:Yes,No'],

            'que_10__1' => ['required', Rule::in(que_10__1)],
            'ans_10__1' => ['required', Rule::in(que_10__1_options)],

            'que_10__2' => ['required', Rule::in(que_10__2)],
            'ans_10__2' => ['nullable', Rule::in(que_10__2_options)],

            'que_10__3' => ['required', Rule::in(que_10__3)],
            'ans_10__3' => ['nullable', Rule::in(que_10__3_options)],

            'que_11__1' => ['required', Rule::in(que_11__1)],
            'ans_11__1' => ['required', 'in:Yes,No'],

            'que_11__2' => ['required', Rule::in(que_11__2)],

            'que_12__1' => ['required', Rule::in(que_12__1)],
            'ans_12__1' => ['required', 'in:Yes,No'],

            'que_12__2' => ['required', Rule::in(que_12__2)],

            'que_13__1' => ['required', Rule::in(que_13__1)],
            'ans_13__1' => ['required', 'in:Yes,No'],

            'que_13__2' => ['required', Rule::in(que_13__2)],

            'que_14__1' => ['required', Rule::in(que_14__1)],
            'ans_14__1.*' => ['required', Rule::in(que_14__1_options)],

            'que_14__2' => ['required', Rule::in(que_14__2)],

            'que_14__3' => ['required', Rule::in(que_14__3)],

            'que_15__1' => ['required', Rule::in(que_15__1)],
            'ans_15__1.*' => ['required', Rule::in(que_15__1_options)],

            'que_15__2' => ['required', Rule::in(que_15__2)],

            'que_15__3' => ['required', Rule::in(que_15__3)],

            'que_16' => ['required', Rule::in(que_16)],
            'ans_16' => ['required', Rule::in(que_16_options)],

            'que_17' => ['required', Rule::in(que_17)],
            'ans_17' => ['required', Rule::in(que_17_options)],

            'que_18' => ['required', Rule::in(que_18)],
            'ans_18' => ['required', Rule::in(que_18_options)],

            'que_19' => ['required', Rule::in(que_19)],
            'ans_19' => ['required', Rule::in(que_19_options)],

            'que_20' => ['required', Rule::in(que_20)],
            'ans_20' => ['required', Rule::in(que_20_options)],

            'que_21' => ['required', Rule::in(que_21)],
            'ans_21.*' => ['required', Rule::in(que_21_options)],

            'que_22' => ['required', Rule::in(que_22)],
            'ans_22.*' => ['required', Rule::in(que_22_options)],

            'que_23' => ['required', Rule::in(que_23)],
            'ans_23' => ['required', Rule::in(que_23_options)],

            'que_24' => ['required', Rule::in(que_24)],

            'ans_25' => ['required'], // all data is correct agreement validation
        ];
    }

}
