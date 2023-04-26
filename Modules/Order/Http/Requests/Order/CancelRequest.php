<?php
/**
 * Created by PhpStorm
 * User: Mr Son
 * Date: 4/22/2020
 * Time: 4:28 PM
 */

namespace Modules\Order\Http\Requests\Order;


use MyCore\Http\Request\BaseFormRequest;

class CancelRequest extends BaseFormRequest
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
            'order_id' => 'integer|required',
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
            'order_id.integer'     => __('Kiểu dữ liệu không hợp lệ.'),
            'order_id.required'     => __('Mã đơn hàng không được trống.'),
            'type.required'     => __('Loại không được trống.'),
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
            'order_id'     => 'strip_tags|trim',
            'type'     => 'strip_tags|trim',
        ];
    }
}