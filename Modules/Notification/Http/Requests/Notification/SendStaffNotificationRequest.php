<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 09/04/2021
 * Time: 14:05
 */

namespace Modules\Notification\Http\Requests\Notification;

use MyCore\Http\Request\BaseFormRequest;

class SendStaffNotificationRequest extends BaseFormRequest
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
            'key' => 'required',
            'customer_id' => 'required|integer',
            'object_id' => 'integer|nullable'
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
            'customer_id.required'     => __('Mã thông báo là thông tin bắt buộc.'),
            'customer_id.integer'     => __('Kiểu dữ liệu không hợp lệ.'),
            'key.required' => __('Hãy nhập key.'),
            'object_id.integer' => __('Kiểu dữ liệu không hợp lệ.')
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
            'customer_id'     => 'strip_tags|trim',
            'key'     => 'strip_tags|trim',
            'object_id'     => 'strip_tags|trim'
        ];
    }
}