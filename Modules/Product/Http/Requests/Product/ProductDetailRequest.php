<?php
/**
 * Created by PhpStorm   .
 * User: Mr Son
 * Date: 2020-03-19
 * Time: 2:03 PM
 * @author SonDepTrai
 */

namespace Modules\Product\Http\Requests\Product;


use MyCore\Http\Request\BaseFormRequest;

class ProductDetailRequest extends BaseFormRequest
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
            'product_id' => 'integer|required',
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
            'product_id.integer'     => __('Kiểu dữ liệu không hợp lệ.'),
            'product_id.required' => __('Hãy chọn sản phẩm.'),
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
            'brand_code'  => 'strip_tags|trim',
            'product_id'  => 'strip_tags|trim',
        ];
    }
}