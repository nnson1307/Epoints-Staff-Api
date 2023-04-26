<?php
/**
 * Created by PhpStorm   .
 * User: Mr Son
 * Date: 2020-03-20
 * Time: 3:46 PM
 * @author SonDepTrai
 */

namespace Modules\Order\Http\Requests\Order;


use MyCore\Http\Request\BaseFormRequest;

class DiscountMemberRequest extends BaseFormRequest
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
            'amount' => 'required',
            'brand_code' => 'required',
            'customer_id' => 'required'
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
            'amount.required' => __('Thành tiền không được trống.'),
            'brand_code.required' => __('Brand code là thông tin bắt buộc'),
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
            'brand_code' => 'strip_tags|trim',
            'amount' => 'strip_tags|trim',
            'customer_id' => 'strip_tags|trim',
        ];
    }
}