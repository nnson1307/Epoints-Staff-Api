<?php
/**
 * Created by PhpStorm   .
 * User: Mr Son
 * Date: 2020-03-19
 * Time: 10:31 AM
 * @author SonDepTrai
 */

namespace Modules\Product\Http\Requests\Product;


use MyCore\Http\Request\BaseFormRequest;

class ProductAllListRequest extends BaseFormRequest
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
            'product_name' => 'nullable',
            'product_category_id' => 'integer|nullable'
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
            'product_category_id.integer'     => __('Kiểu dữ liệu không hợp lệ.')
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
            'product_category_id'     => 'strip_tags|trim'
        ];
    }
}