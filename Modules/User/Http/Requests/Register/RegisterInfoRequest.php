<?php
/**
 * Created by PhpStorm   .
 * User: Mr Son
 * Date: 2020-02-26
 * Time: 10:40 AM
 * @author SonDepTrai
 */

namespace Modules\User\Http\Requests\Register;


use MyCore\Http\Request\BaseFormRequest;

class RegisterInfoRequest extends BaseFormRequest
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
            'phone'         => 'required|digits:10', //|phone
            'password'      => 'required|min:1|confirmed', // password_confirmation
            'full_name'      => 'required|max:50',
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
            'phone.digits'         => __('Số điện thoại không đúng định dạng'),
            'phone.phone'          => __('Số điện thoại không đúng định dạng'),
            'full_name.required'    => __('Họ tên là thông tin bắt buộc'),
            'full_name.max'    => __('Họ tên tối đa 50 kí tự'),
            'password.required'    => __('Mật khẩu là thông tin bắt buộc'),
            'password.min'         => __('Vui lòng chọn mật khẩu từ 1 - 20 ký tự'),
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
            'phone'     => 'strip_tags|trim',
            'full_name'  => 'strip_tags|trim'
        ];
    }
}