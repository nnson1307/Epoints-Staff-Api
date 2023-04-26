<?php
namespace Modules\Notification\Http\Requests\Notification;


use MyCore\Http\Request\BaseFormRequest;

/**
 * Class NotificationListRequest
 * @package Modules\Notification\Http\Requests
 * @author BangNB
 * @since Sep, 2019
 */
class NotificationListRequest extends BaseFormRequest
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
            'is_read' => 'integer'
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
            'is_read.integer'     => __('Kiểu dữ liệu không hợp lệ.')
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
            'district_id'     => 'strip_tags|trim',
            'is_read'     => 'strip_tags|trim'
        ];
    }
}