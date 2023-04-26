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

class VerifyOtpRequest extends BaseFormRequest
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
            'otp'          => 'required',
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
            'otp.required'         => __('Mã otp là thông tin bắt buộc'),
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
            'otp'         => 'strip_tags|trim',
        ];
    }
}