<?php
/**
 * Created by PhpStorm   .
 * User: Mr Son
 * Date: 2020-03-24
 * Time: 2:49 PM
 * @author SonDepTrai
 */

namespace Modules\Booking\Http\Requests\Booking;


use MyCore\Http\Request\BaseFormRequest;

class GetListCustomerRequest extends BaseFormRequest
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
            'page' => 'integer',
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
            'page.integer'     => __('Kiểu dữ liệu không hợp lệ.'),
            'customer_id.required'     => __('Mã khách hàng không được trống.'),
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
            'page'     => 'strip_tags|trim',
            'customer_id'     => 'strip_tags|trim'
        ];
    }
}