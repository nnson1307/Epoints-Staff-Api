<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 26/05/2021
 * Time: 14:34
 */

namespace Modules\Report\Http\Requests\RevenueOrder;

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
            'range_date' => 'required',
            "branch_id" => 'nullable'
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
            'branch_id' => 'strip_tags|trim',
        ];
    }
}