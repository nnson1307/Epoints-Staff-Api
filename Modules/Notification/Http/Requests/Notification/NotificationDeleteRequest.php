<?php
namespace Modules\Notification\Http\Requests\Notification;


use MyCore\Http\Request\BaseFormRequest;

/**
 * Class NotificationDetailRequest
 * @package Modules\Notification\Http\Requests
 * @author BangNB
 * @since Sep, 2019
 */
class NotificationDeleteRequest extends BaseFormRequest
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
            'notification_id' => 'integer',
            'brand_id' => 'integer'
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
            'notification_id.integer'     => __('Kiểu dữ liệu không hợp lệ.'),
            'brand_id.integer'     => __('Kiểu dữ liệu không hợp lệ.')
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
            'notification_id'     => 'strip_tags|trim',
            'brand_id'     => 'strip_tags|trim'
        ];
    }
}