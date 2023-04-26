<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 03/10/2022
 * Time: 09:16
 */

namespace Modules\Ticket\Http\Requests\Ticket;

use MyCore\Http\Request\BaseFormRequest;


class TaskOfTicketRequest extends BaseFormRequest
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
            'ticket_id'    => 'required'
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
            'ticket_id.required' => __('ticket_id là thông tin bắt buộc')
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
            'ticket_id' => 'strip_tags|trim'
        ];
    }
}