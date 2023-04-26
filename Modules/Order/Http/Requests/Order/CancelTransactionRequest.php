<?php
/**
 * Created by PhpStorm
 * User: Mr Son
 * Date: 10/15/2020
 * Time: 2:26 PM
 */

namespace Modules\Order\Http\Requests\Order;


use MyCore\Http\Request\BaseFormRequest;

class CancelTransactionRequest extends BaseFormRequest
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
            'order_id' => 'integer|required',
            'AccessCode' => 'required'
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
            'order_id.integer'     => __('Kiểu dữ liệu không hợp lệ.'),
            'order_id.required'     => __('Mã đơn hàng không được trống.'),
            'AccessCode.required' => __('Hãy nhập access code.')
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
            'order_id'     => 'strip_tags|trim',
            'AccessCode'     => 'strip_tags|trim',
        ];
    }
}