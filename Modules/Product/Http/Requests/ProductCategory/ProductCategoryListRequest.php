<?php
/**
 * Created by PhpStorm
 * User: Mr Son
 * Date: 08-04-02020
 * Time: 9:18 AM
 */

namespace Modules\Product\Http\Requests\ProductCategory;


use MyCore\Http\Request\BaseFormRequest;

class ProductCategoryListRequest extends BaseFormRequest
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
            'category_name' => 'nullable',
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
        ];
    }
}