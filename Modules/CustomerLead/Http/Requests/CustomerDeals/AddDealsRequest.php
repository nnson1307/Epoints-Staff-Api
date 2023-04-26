<?php

/**
 * Created by PhpStorm   .
 * User: Mr To
 * Date: 2022-09-13
 * Time: 3:00 PM
 * @author doanhongto
 */

namespace Modules\CustomerLead\Http\Requests\CustomerDeals;


use MyCore\Http\Request\BaseFormRequest;
use Illuminate\Validation\Rule;

class AddDealsRequest extends BaseFormRequest
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
            'type_customer' => ['required',
                Rule::in(['lead', 'customer'])
            ],
            'customer_code' => 'required',
            'deal_name' => 'required',
            'pipeline_code'=> 'required',
            'journey_code'=> 'required',
            'sale_id' => 'integer|required',
            'order_source_id'=> 'integer|nullable',
            'phone' => 'string|required|unique:cpo_deals,phone,' . ',deal_code,is_deleted,0',//
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
            'type_customer.required'     => __('Loại khách hàng không được để trống.'),
            'type_customer.in'     => __('Loại khách hàng không đúng. Loại Khách hàng phải là Khách hàng tiềm năng hoặc Khách hàng'),
            'customer_code.required'     => __('Mã khách hàng không được để trống.'),
            'deal_name.required' => __('Tên deal không được để trống.'),
            'pipeline_code.required'     => __('Mã pipeline không được để trống.'),
            'journey_code.required'     => __('Hành trình không được để trống.'),
            'sale_id.integer' => __('Mã người được phân bổ không đúng kiểu dữ liệu.'),
            'sale_id.required' => __('Mã người được phân bổ không được để trống.'),
            'order_source_id.integer'     => __('Id nguồn đơn hàng không đúng kiểu dữ liệu.'),
            'probability.nullable'     => __('Vui lòng nhập xác suất.'),
            'phone.string'     => __('Số điện thoại không đúng kiểu dữ liệu.'),
            'phone.required'     => __('Số điện thoại không được để trống.'),
            'phone.unique'     => __('Số điện thoại đã tồn tại.'),
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

        ];
    }
}