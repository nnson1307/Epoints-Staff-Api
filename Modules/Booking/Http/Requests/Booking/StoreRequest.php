<?php
/**
 * Created by PhpStorm   .
 * User: Mr Son
 * Date: 2020-02-18
 * Time: 2:15 PM
 * @author SonDepTrai
 */

namespace Modules\Booking\Http\Requests\Booking;


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
            'branch_id' => 'integer|required',
            'customer_id' => 'required',
            'date' => 'required|date_format:d/m/Y',
            'time' => 'required|date_format:H:i',
            'staff_id' => 'integer|nullable',
            'total' => 'required',
            'discount' => 'required',
            'amount' => 'required'
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
            'branch_id.integer'     => __('Kiểu dữ liệu không hợp lệ.'),
            'branch_id.required' => __('Hãy chọn chi nhánh.'),
            'customer_id.required'    => __('Vui lòng chọn khách hàng'),
            'date.required' => __('Ngày đặt lịch không được trống.'),
            'date.date_format' => __('Kiểu dữ liệu không hợp lệ.'),
            'time.required' => __('Thời gian đặt lịch không được trống.'),
            'time.date_format' => __('Kiểu dữ liệu không hợp lệ.'),
            'staff_id.integer' => __('Kiểu dữ liệu không hợp lệ.'),
            'total.required' => __('Hãy nhập tổng tiền.'),
            'discount.required' => __('Hãy nhập giảm giá.'),
            'amount.required' => __('Hãy nhập thành tiền.'),
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
            'branch_id' => 'strip_tags|trim',
            'customer_id' => 'strip_tags|trim',
            'date' => 'strip_tags|trim',
            'time' => 'strip_tags|trim',
            'appointment_source_id' => 'strip_tags|trim',
            'staff_id' => 'strip_tags|trim',
            'room_id' => 'strip_tags|trim',
            'total' => 'strip_tags|trim',
            'discount' => 'strip_tags|trim',
            'amount' => 'strip_tags|trim',
            'description' => 'strip_tags|trim'
        ];
    }
}