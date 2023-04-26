<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 26/05/2021
 * Time: 17:38
 */

namespace Modules\Report\Http\Requests\Inventory;


use MyCore\Http\Request\BaseFormRequest;

class TotalRequest extends BaseFormRequest
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
            'date' => 'required',
            "warehouse_id" => 'nullable|integer'
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
            'date.required' => __('Hãy chọn ngày'),
            'warehouse_id.required' => __('Hãy nhập mã kho'),
            'warehouse_id.integer' => __('Kiểu dữ liệu không hợp lệ.'),
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
            'date' => 'strip_tags|trim',
            'warehouse_id' => 'strip_tags|trim',
        ];
    }
}