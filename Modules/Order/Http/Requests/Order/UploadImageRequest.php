<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 08/05/2021
 * Time: 13:44
 */

namespace Modules\Order\Http\Requests\Order;

use MyCore\Http\Request\BaseFormRequest;
use Illuminate\Validation\Rule;

class UploadImageRequest extends BaseFormRequest
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
            'order_code' => 'required',
            'brand_code' => 'required',
            'type' => [
                'required',
                Rule::in(['before', 'after'])
            ],
            'link' => 'required',
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
            'order_code.required'     => __('Mã đơn hàng không được trống.'),
            'brand_code.required' => __('Brand code là thông tin bắt buộc'),
            'type.in' => __('Type không đúng định dạng, phải là trước hoặc sau.'),
            'link.required' => __('Hãy chọn hình ảnh'),
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
            'order_code' => 'strip_tags|trim',
            'type' => 'strip_tags|trim',
            'link' => 'strip_tags|trim',
        ];
    }
}