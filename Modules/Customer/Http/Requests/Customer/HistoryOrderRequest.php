<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 11/05/2021
 * Time: 14:14
 */

namespace Modules\Customer\Http\Requests\Customer;

use MyCore\Http\Request\BaseFormRequest;

class HistoryOrderRequest extends BaseFormRequest
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
            'brand_code' => 'required',
            'page' => 'integer',
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
            'brand_code.required' => __('Brand code là thông tin bắt buộc'),
            'page.integer'     => __('Kiểu dữ liệu không hợp lệ.'),
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
            'brand_code' => 'strip_tags|trim',
            'page' => 'strip_tags|trim',
            'search' => 'strip_tags|trim',
            'customer_id' => 'strip_tags|trim',
        ];
    }
}