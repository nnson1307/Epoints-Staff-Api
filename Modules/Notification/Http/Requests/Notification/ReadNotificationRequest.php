<?php
/**
 * Created by PhpStorm
 * User: Mr Son
 * Date: 9/11/2020
 * Time: 11:19 AM
 */

namespace Modules\Notification\Http\Requests\Notification;


use MyCore\Http\Request\BaseFormRequest;

class ReadNotificationRequest extends BaseFormRequest
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
            'staff_notification_id' => 'required|integer'
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
            'staff_notification_id.required'     => __('Mã thông báo là thông tin bắt buộc.'),
            'staff_notification_id.integer'     => __('Kiểu dữ liệu không hợp lệ.')
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
            'staff_notification_id'     => 'strip_tags|trim'
        ];
    }
}