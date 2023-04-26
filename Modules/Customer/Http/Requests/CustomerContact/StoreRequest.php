<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 24/05/2021
 * Time: 14:31
 */

namespace Modules\Customer\Http\Requests\CustomerContact;

use MyCore\Http\Request\BaseFormRequest;

class StoreRequest extends BaseFormRequest
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
            'customer_id' => 'required',
            'phone'         => 'required|digits:10', //|phone
            'full_name'      => 'required|max:50',
            'address' => 'required|max:200',
            'province_id' => 'required|integer',
            'district_id' => 'required|integer',
            'ward_id' => 'required',
            'address_default' => 'required'
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
            'customer_id.required' => __('Mã khách hàng là thông tin bắt buộc'),
            'province_id.required' => __('Tỉnh thành không được trống.'),
            'province_id.integer' => __('Kiểu dữ liệu không hợp lệ.'),
            'district_id.required' => __('Quận huyện không được trống.'),
            'district_id.integer' => __('Kiểu dữ liệu không hợp lệ.'),
            'ward_id.required' => __('Phường xã không được trống.'),
            'address.required' => __('Địa chỉ không được trống.'),
            'address.max' => __('Địa chỉ tối đa 200 kí tự.'),
            'phone.required'       => __('Số điện thoại là thông tin bắt buộc'),
            'phone.digits'         => __('Số điện thoại không đúng định dạng'),
            'phone.phone'          => __('Số điện thoại không đúng định dạng'),
            'full_name.required'    => __('Họ tên là thông tin bắt buộc'),
            'full_name.max'    => __('Họ tên tối đa 50 kí tự'),
            'address_default.required' => __('Hãy nhập địa chỉ mặc định')
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
            'province_id' => 'strip_tags|trim',
            'district_id' => 'strip_tags|trim',
            'address' => 'strip_tags|trim',
            'phone'     => 'strip_tags|trim',
            'full_name'  => 'strip_tags|trim',
            'address_default'  => 'strip_tags|trim'
        ];
    }
}