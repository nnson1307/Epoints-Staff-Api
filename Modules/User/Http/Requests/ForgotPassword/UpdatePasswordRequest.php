<?php
/**
 * Created by PhpStorm   .
 * User: Mr Son
 * Date: 2020-02-28
 * Time: 2:30 PM
 * @author SonDepTrai
 */

namespace Modules\User\Http\Requests\ForgotPassword;


use MyCore\Http\Request\BaseFormRequest;

class UpdatePasswordRequest extends BaseFormRequest
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
            'phone'         => 'required|max:20',
            'password'      => 'required|min:1|confirmed', // password_confirmation
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
            'phone.required'       => __('Số điện thoại là thông tin bắt buộc'),
            'phone.max'            => __('Số điện thoại không hợp lệ'),
            'password.required'    => __('Mật khẩu là thông tin bắt buộc'),
            'password.min'         => __('Vui lòng chọn mật khẩu từ 6 - 20 ký tự'),
            'password.max'         => __('Vui lòng chọn mật khẩu từ 6 - 20 ký tự'),
            'password.confirmed'   => __('Mật khẩu nhập lại cần trùng với Mật khẩu mới')
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
            'phone'        => 'strip_tags|trim',
        ];
    }
}