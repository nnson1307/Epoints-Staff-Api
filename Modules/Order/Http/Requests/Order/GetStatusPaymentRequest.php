<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 22/12/2021
 * Time: 15:38
 */

namespace Modules\Order\Http\Requests\Order;

use MyCore\Http\Request\BaseFormRequest;

class GetStatusPaymentRequest extends BaseFormRequest
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
            'payment_transaction_code' => 'required'
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
            'payment_transaction_code.required' => __('Mã giao dịch không được trống.'),


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
            'payment_transaction_code' => 'strip_tags|trim',
        ];
    }
}