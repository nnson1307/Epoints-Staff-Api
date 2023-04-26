<?php
/**
 * Created by PhpStorm
 * User: Mr Son
 * Date: 10/10/2020
 * Time: 3:00 PM
 */

namespace Modules\Order\Http\Requests\Order;


use MyCore\Http\Request\BaseFormRequest;

class OrderPaymentRequest extends BaseFormRequest
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
            'order_id' => 'required',
            'order_code' => 'required',
            'amount_bill' => 'required',
            'total_amount_receipt' => 'required',
//            'order_source_id' => 'required',
            'note' => 'nullable|max:190'
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
            'order_id.required' => __('Id đơn hàng không được trống.'),
            'order_code.required' => __('Mã đơn hàng không được trống.'),
            'amount_bill.required' => __('Hãy nhập tiền cần thanh toán.'),
            'total_amount_receipt.required' => __('Hãy nhập tổng tiền đã thanh toán.'),
            'order_source_id.required' => __('Hãy nhập nguồn đơn hàng.'),
            'note.max' => __('Ghi chú tối đa 190 ký tự')
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
            'customer_id'     => 'strip_tags|trim',
            'order_id'     => 'strip_tags|trim',
            'order_code' => 'strip_tags|trim',
            'amount_bill'     => 'strip_tags|trim',
            'total_amount_receipt'     => 'strip_tags|trim',
            'order_source_id'     => 'strip_tags|trim',
            'note'     => 'strip_tags|trim',
        ];
    }
}