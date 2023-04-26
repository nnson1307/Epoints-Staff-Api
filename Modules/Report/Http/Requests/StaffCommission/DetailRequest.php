<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 27/05/2021
 * Time: 11:44
 */

namespace Modules\Report\Http\Requests\StaffCommission;

use MyCore\Http\Request\BaseFormRequest;

class DetailRequest extends BaseFormRequest
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
            'range_date' => 'required',
            'staff_id' => 'required',
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
            'range_date.required' => __('Hãy chọn ngày đến ngày'),
            'staff_id.required' => __('Hãy chọn nhân viên'),
            'page.integer'     => __('Kiểu dữ liệu không hợp lệ.'),
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
            'range_date' => 'strip_tags|trim',
            'staff_id' => 'strip_tags|trim',
            'page' => 'strip_tags|trim'
        ];
    }
}