<?php


namespace Modules\User\Http\Requests\Authen;


use Illuminate\Validation\Rule;
use MyCore\Http\Request\BaseFormRequest;

class QuickLoginRequest extends BaseFormRequest
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
            'platform' => [
                'required',
                Rule::in(['android', 'ios'])
            ],
            'device_token' => 'required',
            'imei' => 'required'
        ];
    }

    /**
     * Customize message
     *
     * @return array
     */
    public function messages()
    {
        return [
            'platform.required' => __('Platform là thông tin bắt buộc'),
            'platform.in' => __('Platform không đúng. Platform phải là android hoặc ios'),
            'imei.required' => __('IMEI là thông tin bắt buộc'),
            'device_token.required' => __('Device Token là thông tin bắt buộc')
        ];
    }

    /**
     *  Filters to be applied to the input.
     *
     * @return array
     */
    public function filters()
    {
        return [
            'platform' => 'strip_tags|trim',
            'imei' => 'strip_tags|trim',
            'device_token' => 'strip_tags|trim'
        ];
    }
}
