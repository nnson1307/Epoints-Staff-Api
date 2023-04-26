<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 27/09/2022
 * Time: 22:43
 */

namespace Modules\Warranty\Http\Requests\Maintenance;

use MyCore\Http\Request\BaseFormRequest;

class UpdateRequest extends BaseFormRequest
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
            'maintenance_id' => 'required',
            'maintenance_code' => 'required',
            'customer_code' => 'required',
            'object_type' => 'required',
            'object_type_id' => 'required',
            'object_code' => 'required',
            'staff_id' => 'required',
            'date_estimate_delivery' => 'required'
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
            'maintenance_id.required'     => __('Hãy nhập mã phiếu bảo trì.'),
            'maintenance_code.required'     => __('Hãy nhập mã phiếu bảo trì.'),
            'customer_code.required'     => __('Hãy chọn khách hàng.'),
            'object_type.required' => __('Hãy chọn loại đối tượng'),
            'object_type_id.required' => __('Hãy chọn đối tượng bảo hành'),
            'object_code.required' => __('Hãy chọn đối tượng bảo hành'),
            'staff_id.required' => __('Hãy chọn nhân viên thực hiện'),
            'date_estimate_delivery.required' => __('Hãy chọn ngày trả hàng dự kiến')
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
            'customer_code' => 'strip_tags|trim',
            'warranty_code' => 'strip_tags|trim',
            'warranty_value' => 'strip_tags|trim',
            'maintenance_cost' => 'strip_tags|trim',
            'insurance_pay' => 'strip_tags|trim',
            'amount_pay' => 'strip_tags|trim',
            'total_amount_pay' => 'strip_tags|trim',
            'staff_id' => 'strip_tags|trim',
            'object_type' => 'strip_tags|trim',
            'object_type_id' => 'strip_tags|trim',
            'object_code' => 'strip_tags|trim',
            'object_serial' => 'strip_tags|trim',
            'object_status' => 'strip_tags|trim',
            'maintenance_content' => 'strip_tags|trim',
            'date_estimate_delivery' => 'strip_tags|trim',
            'status' => 'strip_tags|trim'
        ];
    }
}