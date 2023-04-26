<?php
/**
 * Created by PhpStorm
 * User: Mr Son
 * Date: 6/12/2020
 * Time: 10:50 AM
 */

namespace Modules\Product\Http\Requests\ProductCategory;


use MyCore\Http\Request\BaseFormRequest;

class GetCategoryETLRequest extends BaseFormRequest
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
            'category_name' => 'required|max:250',
            'category_uuid' => 'required'
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
            'category_name.required' => __('Hãy nhập tên loại sản phẩm.'),
            'category_name.max'     => __('Tên loại sản phẩm tối đa 250 kí tự.'),
            'category_uuid.required' => __('Hãy nhập category uuid.')
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
            'category_name' => 'strip_tags|trim',
            'category_uuid' => 'strip_tags|trim',
        ];
    }
}