<?php
/**
 * Created by PhpStorm
 * User: Mr Son
 * Date: 6/12/2020
 * Time: 5:11 PM
 */

namespace Modules\Product\Http\Requests\Product;


use MyCore\Http\Request\BaseFormRequest;

class GetProductETLRequest extends BaseFormRequest
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
            'category_uuid' => 'nullable',
            'product_uuid' => 'required',
            'product_name' => 'required|max:250',
            'cost' => 'required',
            'price_standard' => 'required'
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
            'category_uuid.required'     => __('Hãy nhập category_uuid.'),
            'product_uuid.required'     => __('Hãy nhập product uuid.'),
            'product_name.required' => __('Hãy nhập tên sản phẩm.'),
            'product_name.max' => __('Tên sản phẩm tối đa 250 kí tự.'),
            'cost.required' => __('Tên sản phẩm tối đa 250 kí tự.')
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

        ];
    }
}