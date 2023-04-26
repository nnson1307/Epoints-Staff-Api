<?php
/**
 * Created by PhpStorm
 * User: Mr Son
 * Date: 10/23/2020
 * Time: 2:31 PM
 */

namespace Modules\Product\Http\Requests\Product;


use MyCore\Http\Request\BaseFormRequest;

class ScanProductRequest extends BaseFormRequest
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
            'product_code' => 'required',
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
            'product_code.required'     => __('Hãy nhập mã sản phẩm'),
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
            'product_code'     => 'strip_tags|trim',
        ];
    }
}