<?php
/**
 * Created by PhpStorm
 * User: Mr Son
 * Date: 6/6/2020
 * Time: 11:20 AM
 */

namespace Modules\User\Http\Requests\Authen;


use Illuminate\Validation\Rule;
use MyCore\Http\Request\BaseFormRequest;

class LoginCarrierRequest extends BaseFormRequest
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
            'user_name'    => 'required|min:3',
            'password' => 'required|min:6',
//            'phone1' => 'required|min:3',
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
            'user_name.required' => __('Tên đăng nhập là thông tin bắt buộc'),
            'user_name.min' => __('Tên đăng nhập không đúng định dạng'),
            'password.required' => __('Mật khẩu là thông tin bắt buộc'),
            'password.min' => __('Vui lòng chọn mật khẩu từ 6 - 20 ký tự'),
            'platform.required' => __('Platform là thông tin bắt buộc'),
            'platform.in' => __('Platform không đúng. Platform phải là android hoặc ios'),
            'imei.required' => __('IMEI là thông tin bắt buộc'),
            'device_token.required' => __('Device Token là thông tin bắt buộc'),
            'phone1.required' => __('Số điện thoại là thông tin bắt buộc'),
            'phone1.min' => __('Số điện thoại không đúng định dạng'),
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
            'user_name' => 'strip_tags|trim',
            'platform' => 'strip_tags|trim',
            'imei' => 'strip_tags|trim',
            'device_token' => 'strip_tags|trim'
        ];
    }
}