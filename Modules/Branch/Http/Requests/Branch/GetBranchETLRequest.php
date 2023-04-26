<?php
/**
 * Created by PhpStorm
 * User: Mr Son
 * Date: 6/12/2020
 * Time: 2:59 PM
 */

namespace Modules\Branch\Http\Requests\Branch;


use MyCore\Http\Request\BaseFormRequest;

class GetBranchETLRequest extends BaseFormRequest
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
            'branch_name' => 'required|max:250',
            'branch_code' => 'required',
            'site_id' => 'required|integer'
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
            'branch_name.required' => __('Hãy nhập tên chi nhánh.'),
            'branch_name.max'     => __('Tên chi nhánh tối đa 250 kí tự.'),
            'branch_code.required' => __('Hãy nhập mã chi nhánh.'),
            'site_id.required' => __('Hãy nhập site_id.'),
            'site_id.integer' => __('Kiểu dữ liệu không hợp lệ.'),
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
            'branch_name' => 'strip_tags|trim',
            'branch_code' => 'strip_tags|trim',
            'site_id' => 'strip_tags|trim',
        ];
    }
}