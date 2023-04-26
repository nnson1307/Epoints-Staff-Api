<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 14/01/2022
 * Time: 11:59
 */

namespace Modules\Order\Http\Requests\Order;

use MyCore\Http\Request\BaseFormRequest;

class CreateQrCodeRequest extends BaseFormRequest
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
            'object_type' => 'required',
            'object_id' => 'integer|required',
            'payment_method_code' => 'required',
            'money' => 'required'
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
            'object_type.required'     => __('Loại đối tượng không được trống.'),
            'object_id.integer'     => __('Kiểu dữ liệu không hợp lệ.'),
            'object_id.required'     => __('Mã đơn hàng không được trống.'),
            'payment_method_code.required'     => __('Mã phương thức thanh toán không được trống.'),
            'money.required'     => __('Tiền thanh toán không được trống.'),
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
            'payment_method_code' => 'strip_tags|trim',
            'money' => 'strip_tags|trim',
        ];
    }
}