<?php
/**
 * Created by PhpStorm
 * User: Mr Son
 * Date: 10/14/2020
 * Time: 2:11 PM
 */

namespace Modules\Order\Http\Requests\Order;


use MyCore\Http\Request\BaseFormRequest;

class CheckTransportChargeRequest extends BaseFormRequest
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
            'customer_contact_code' => 'required',
            'customer_id' => 'required',
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
            'customer_contact_code.required'     => __('Hãy nhập mã nhận hàng.'),
            'customer_id.required' => __('Mã khách hàng là thông tin bắt buộc'),
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
            'customer_contact_code'     => 'strip_tags|trim',
            'customer_id'     => 'strip_tags|trim',
        ];
    }
}