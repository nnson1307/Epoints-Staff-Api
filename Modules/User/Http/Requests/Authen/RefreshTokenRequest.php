<?php
namespace Modules\User\Http\Requests\Authen;

use MyCore\Http\Request\BaseFormRequest;
use Illuminate\Validation\Rule;

/**
 * Class RefreshTokenRequest
 * @package Modules\User\Http\Requests\Authen
 * @author DaiDP
 * @since Aug, 2019
 */
class RefreshTokenRequest extends BaseFormRequest
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
            'refresh_token' => 'required',
//            'platform'      => [
//                'required',
//                Rule::in(['android', 'ios'])
//            ],
//            'device_token'  => 'required',
//            'imei'          => 'required',
            'brand_code' => 'required'
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
            'refresh_token.required' => __('Refresh token là thông tin bắt buộc'),
            'platform.required'      => __('Platform là thông tin bắt buộc'),
            'platform.in'            => __('Platform không đúng. Platform phải là android hoặc ios'),
            'imei.required'          => __('IMEI là thông tin bắt buộc'),
            'device_token.required'  => __('Device Token là thông tin bắt buộc'),
            'brand_code.required' => __('Brand code là thông tin bắt buộc'),
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
            'refresh_token' => 'strip_tags|trim',
            'platform'      => 'strip_tags|trim',
            'imei'          => 'strip_tags|trim',
            'device_token'  => 'strip_tags|trim',
            'brand_code' => 'strip_tags|trim',
        ];
    }
}