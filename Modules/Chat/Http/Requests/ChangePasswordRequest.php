<?php

namespace Modules\Chat\Http\Requests;

use MyCore\Http\Request\BaseFormRequest;

/**
 * Class LoginRequest
 * @package Modules\User\Http\Requests\Authen
 * @author DaiDP
 * @since Aug, 2019
 */
class ChangePasswordRequest extends BaseFormRequest
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
            're_password'  => 'required|same:password',
            'password'      => 'required|min:6|max:20'
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
            're_password.required'  => __('Mật khẩu hiện tại không chính xác'),
            'password.required'      => __('Mật khẩu là thông tin bắt buộc'),
            're_password.same'     => __('Mật khẩu nhập lại cần trùng với mật khẩu mới'),
            'password.min'           => __('Vui lòng chọn mật khẩu từ 6 đến 20 ký tự'),
            'password.max'           => __('Vui lòng chọn mật khẩu từ 6 đến 20 ký tự')
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
            'user_id'     => 'strip_tags|trim',
            'password'    => 'strip_tags|trim'
        ];
    }
}