<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 13/05/2021
 * Time: 11:02
 */

namespace Modules\Order\Http\Requests\Order;

use MyCore\Http\Request\BaseFormRequest;

class GetVoucherRequest extends BaseFormRequest
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
            "customer_id" => "required",
            'total_amount' => 'required',
            'voucher_code' => 'required'
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
            'total_amount.required' => __('Thành tiền không được trống.'),
//            'total_amount.integer' => __('Kiểu dữ liệu không hợp lệ.'),
            'voucher_code.required' => __('Hãy nhập mã giảm giá.'),
            'customer_id.required' => __('Hãy nhập mã khách hàng'),
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
            'total_amount' => 'strip_tags|trim',
            'voucher_code' => 'strip_tags|trim',
            'customer_id' => 'strip_tags|trim',
        ];
    }
}