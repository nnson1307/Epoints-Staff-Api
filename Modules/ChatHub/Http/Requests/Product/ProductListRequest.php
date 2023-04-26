<?php
/**
 * Created by PhpStorm   .
 * User: Mr Son
 * Date: 2020-02-22
 * Time: 11:48 AM
 * @author SonDepTrai
 */

namespace Modules\ChatHub\Http\Requests\Product;


use MyCore\Http\Request\BaseFormRequest;

class ProductListRequest extends BaseFormRequest
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
            'product_name' => 'nullable',
            'product_category_id' => 'integer|nullable',
            'brand_code' => 'required'
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
            'product_category_id.integer'     => __('Kiểu dữ liệu không hợp lệ.'),
            'brand_code.required'     => __('Brand code là thông tin bắt buộc.'),
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
            'product_name'     => 'strip_tags|trim',
            'product_category_id'  => 'strip_tags|trim',
            'brand_code'  => 'strip_tags|trim',
        ];
    }
}