<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 08/05/2021
 * Time: 15:31
 */

namespace Modules\Order\Http\Requests\Order;

use MyCore\Http\Request\BaseFormRequest;

class GetPromotionGiftRequest extends BaseFormRequest
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
            'customer_id' => 'required'
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
            'customer_id.required' => __('Mã khách hàng là thông tin bắt buộc'),
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
            'customer_id' => 'strip_tags|trim',
        ];
    }
}