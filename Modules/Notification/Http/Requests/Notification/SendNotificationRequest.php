<?php
/**
 * Created by PhpStorm
 * User: Mr Son
 * Date: 14-04-02020
 * Time: 2:19 PM
 */

namespace Modules\Notification\Http\Requests\Notification;


use MyCore\Http\Request\BaseFormRequest;

class SendNotificationRequest extends BaseFormRequest
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