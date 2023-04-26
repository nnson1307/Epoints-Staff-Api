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
class TicketEditRequest extends BaseFormRequest
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
            'ticket_id'    => 'required',
//            'ticket_issue_group_id' => 'required',
            'ticket_issue_id' => 'required',
            'title' => 'required|max:255',
            'ticket_status_id' => 'required',
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
            'ticket_id.required' => __('ticket_id là thông tin bắt buộc'),
//            'ticket_issue_group_id.required' => __('Nhóm vấn đề là thông tin bắt buộc'),
            'ticket_issue_id.required' => __('Vấn đề là thông tin bắt buộc'),
            'title.required' => __('Tiêu đề là thông tin bắt buộc'),
            'title.max' => __('Tiêu đề vượt quá 255 ký tự'),
            'ticket_status_id.required' => __('Trạng thái là thông tin bắt buộc'),
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