<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 07/05/2021
 * Time: 15:14
 */

namespace Modules\CustomerLead\Http\Requests\CustomerLead;

use MyCore\Http\Request\BaseFormRequest;

class GetListRequest extends BaseFormRequest
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
            'search' => 'strip_tags|trim'
        ];
    }
}