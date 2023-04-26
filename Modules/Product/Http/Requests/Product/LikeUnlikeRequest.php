<?php
/**
 * Created by PhpStorm
 * User: Mr Son
 * Date: 4/24/2020
 * Time: 6:54 PM
 */

namespace Modules\Product\Http\Requests\Product;


use MyCore\Http\Request\BaseFormRequest;

class LikeUnlikeRequest extends BaseFormRequest
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
            'type' => 'required'
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
            'product_id.integer'  => __('Kiểu dữ liệu không hợp lệ.'),
            'product_id.required' => __('Hãy chọn sản phẩm.'),
            'type.required' => __('Hãy nhập loại thích hoặc không thích.')
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
            'type' => 'strip_tags|trim',
            'product_id' => 'strip_tags|trim',
        ];
    }
}