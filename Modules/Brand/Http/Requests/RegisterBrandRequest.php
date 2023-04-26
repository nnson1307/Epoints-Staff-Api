<?php
/**
 * User: HIEUPC
 * Date: 2022-10-17
 */

namespace Modules\Brand\Http\Requests;


use MyCore\Http\Request\BaseFormRequest;

class RegisterBrandRequest extends BaseFormRequest
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
            "brand_name" => 'required|max:200',
            'full_name'      => 'required|max:50',
            'phone'         => 'required|digits:10',
            'email' => 'nullable|email'
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
            'brand_name.required'    => __('Tên thương hiệu là thông tin bắt buộc'),
            'brand_name.max'    => __('Tên thương hiệu tối đa 200 kí tự'),
            'email.email' => __('Email không hợp lệ'),
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
            'phone' => 'strip_tags|trim',
            'full_name' => 'strip_tags|trim',
            'brand_name' => 'strip_tags|trim',
            'email' => 'strip_tags|trim',
        ];
    }
}