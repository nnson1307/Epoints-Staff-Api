<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 24/05/2021
 * Time: 16:39
 */

namespace Modules\Order\Http\Requests\Order;

use MyCore\Http\Request\BaseFormRequest;

class UpdateRequest extends BaseFormRequest
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
            'order_id' => 'required',
            'order_code' => 'required',
            'total' => 'required',
            'discount' => 'required',
            'discount_member' => 'nullable',
            'amount' => 'required',
//            'customer_contact_code' => 'required',
//            'contact_phone' => 'required',
//            'contact_name' => 'required',
//            'full_address' => 'required',
            'payment_method_id' => 'nullable',
            'transport_charge' => 'required',
            'customer_id' => 'required',
            'brand_code' => 'required',
            'order_description' => 'nullable|max:190',
            'process_status' => 'required'
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
            'order_id.required' => __('Id đơn hàng không được trống.'),
            'order_code.required' => __('Mã đơn hàng không được trống.'),
            'total.required' => __('Tổng tiền không được trống.'),
            'total.integer' => __('Kiểu dữ liệu không hợp lệ.'),
            'discount.required' => __('Giảm giá không được trống.'),
            'discount.integer' => __('Kiểu dữ liệu không hợp lệ.'),
            'discount_member.required' => __('Giảm giá thành viên không được trống.'),
            'discount_member.integer' => __('Kiểu dữ liệu không hợp lệ.'),
            'amount.required' => __('Thành tiền không được trống.'),
            'amount.integer' => __('Kiểu dữ liệu không hợp lệ.'),
            'customer_contact_code.required' => __('Hãy nhập mã địa chỉ giao hàng.'),
            'contact_phone.required' => __('Hãy nhập số điện thoại liên hệ.'),
            'contact_name.required' => __('Hãy nhập tên liên hệ.'),
            'full_address.required' => __('Hãy nhập địa chỉ giao hàng.'),
            'payment_method_id.required' => __('Hãy chọn hình thức thanh toán.'),
            'transport_charge.required' => __('Hãy nhập phí vận chuyển.'),
            'brand_code.required' => __('Brand code là thông tin bắt buộc'),
            'customer_id.required' => __('Mã khách hàng là thông tin bắt buộc'),
            'order_description.max' => __('Ghi chú tối đa 190 ký tự'),
            'process_status.required' => __('Trạng thái là thông tin bắt buộc'),
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
            'order_id' => 'strip_tags|trim',
            'order_code' => 'strip_tags|trim',
            'voucher_code' => 'strip_tags|trim',
            'customer_contact_code' => 'strip_tags|trim',
            'contact_phone' => 'strip_tags|trim',
            'contact_name' => 'strip_tags|trim',
            'full_address' => 'strip_tags|trim',
            'transport_charge' => 'strip_tags|trim',
            'brand_code' => 'strip_tags|trim',
            'customer_id' => 'strip_tags|trim',
            'order_description' => 'strip_tags|trim',
            'process_status' => 'strip_tags|trim',
        ];
    }
}