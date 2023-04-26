<?php

namespace Modules\Ticket\Http\Requests\Ticket;

use MyCore\Http\Request\BaseFormRequest;
use Illuminate\Validation\Rule;

/**
 * Class LoginRequest
 * @package Modules\User\Http\Requests\Authen
 * @author DaiDP
 * @since Aug, 2019
 */
class TicketAddRequest extends BaseFormRequest
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
            'title' => 'required|max:255',
            'localtion_id'  => 'required',
            'priority' => 'required',
            'ticket_issue_group_id' => 'required',
            'ticket_issue_id' => 'required',
            'issule_level' => 'required',
            'date_issue' => 'required',
            'queue_process_id' => 'required',
            'operate_by' => 'required',
            'staff_notification_id' =>  'required',
            'customer_address' => 'required',
            'customer_id' => 'required',
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
            'title.required' => __('Tiêu đề là thông tin bắt buộc'),
            'title.max' => __('Tiêu đề vượt quá 255 ký tự'),
            'ticket_issue_id.required' => __('Vấn đề là thông tin bắt buộc'),
            'ticket_status_id.required' => __('Trạng thái là thông tin bắt buộc'),
            'localtion_id.required' => __('Tỉnh / TP là thông tin bắt buộc'),
            'priority.required' => __('Mức độ ưu tiên là thông tin bắt buộc'),
            'issule_level.required' => __('Cấp độ yêu cầu là thông tin bắt buộc'),
            'ticket_issue_group_id.required' => __('Loại yêu cầu là thông tin bắt buộc'),
            'date_issue.required' => __('Thời gian phát sinh là thông tin bắt buộc'),
            'queue_process_id.required' => __('Queue xử lý là thông tin bắt buộc'),
            'operate_by.required' => __('Nhân viên chủ trì là thông tin bắt buộc'),
            'staff_notification_id.required' => __('Nhân viên thông báo là thông tin bắt buộc'),
            'customer_address.required' => __('Địa chỉ là thông tin bắt buộc'),
            'customer_id.required' => __('Khách hàng là thông tin bắt buộc'),
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
            'title' => 'strip_tags|trim',
            'description' => 'strip_tags|trim',
            'customer_address' => 'strip_tags|trim'
        ];
    }
}