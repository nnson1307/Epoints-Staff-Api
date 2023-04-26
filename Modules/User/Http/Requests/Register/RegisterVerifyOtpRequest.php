<?php
/**
 * Created by PhpStorm   .
 * User: Mr Son
 * Date: 2020-02-27
 * Time: 2:00 PM
 * @author SonDepTrai
 */

namespace Modules\User\Http\Requests\Register;


use Illuminate\Validation\Rule;
use MyCore\Http\Request\BaseFormRequest;

class RegisterVerifyOtpRequest extends BaseFormRequest
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
            'otp'          => 'required|digits_between:6,6',
            'phone'         => 'required|digits:10' //|phone
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
            'otp.required'       => __('Mã xác thực là thông tin bắt buộc'),
            'otp.digits_between' => __('Mã xác thực không đúng định dạng'),
            'phone.required'       => __('Số điện thoại là thông tin bắt buộc'),
            'phone.digits'         => __('Số điện thoại không đúng định dạng'),
            'phone.phone'          => __('Số điện thoại không đúng định dạng')
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
            'otp'  => 'strip_tags|trim'
        ];
    }
}