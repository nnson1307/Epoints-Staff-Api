<?php
/**
 * Created by PhpStorm   .
 * User: Mr Son
 * Date: 2020-01-03
 * Time: 9:17 AM
 * @author SonDepTrai
 */

namespace Modules\ChatHub\Http\Requests\Order;


use MyCore\Http\Request\BaseFormRequest;

class OrderListRequest extends BaseFormRequest
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
            'status' => 'nullable',
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
            'brand_code.required' => __('Brand code là thông tin bắt buộc'),
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
            'status'     => 'strip_tags|trim',
            'brand_code' => 'strip_tags|trim',
        ];
    }
}