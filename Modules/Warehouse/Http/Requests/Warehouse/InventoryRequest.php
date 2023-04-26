<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 25/05/2021
 * Time: 17:08
 */

namespace Modules\Warehouse\Http\Requests\Warehouse;

use MyCore\Http\Request\BaseFormRequest;

class InventoryRequest extends BaseFormRequest
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
            'warehouse_id' => 'required',
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
            'warehouse_id.required' => __('Hãy nhập mã kho'),
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
            'page' => 'strip_tags|trim',
            'warehouse_id' => 'strip_tags|trim',
            'product_name' => 'strip_tags|trim',
        ];
    }
}