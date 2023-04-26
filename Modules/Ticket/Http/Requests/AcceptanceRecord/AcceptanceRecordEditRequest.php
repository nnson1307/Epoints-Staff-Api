<?php

namespace Modules\Ticket\Http\Requests\AcceptanceRecord;

use MyCore\Http\Request\BaseFormRequest;
use Illuminate\Validation\Rule;

/**
 * Class LoginRequest
 * @package Modules\User\Http\Requests\Authen
 * @author DaiDP
 * @since Aug, 2019
 */
class AcceptanceRecordEditRequest extends BaseFormRequest
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
        $status = $this->input('status');
        if ($status == 'approve'){
            return [
                'ticket_acceptance_id'    => 'required',
                'title'    => 'required|max:191',
                'status'    => 'required',
                'sign_by'    => 'required|max:255',
                'sign_date'    => 'required',
            ];
        } else {
            return [
                'ticket_acceptance_id'    => 'required',
                'title'    => 'required|max:191',
                'status'    => 'required',
                'sign_by'    => 'max:255',
            ];
        }

    }

    /**
     * Customize message
     *
     * @return array
     */
    public function messages()
    {
        return [
            'ticket_acceptance_id.required' => __('Id biên bản nghiệm thu là thông tin bắt buộc'),
            'title.required' => __('Tên biên bản nghiệm thu là thông tin bắt buộc'),
            'title.max' => __('Tên biên bản nghiệm thu vượt quá 191 ký tự'),
            'status.required' => __('Trạng thái là thông tin bắt buộc'),
            'sign_by.required' => __('Người ký là thông tin bắt buộc'),
            'sign_date.required' => __('Thời gian ký là thông tin bắt buộc'),
            'sign_by.max' => __('Người ký vượt quá 255 ký tự'),
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
            'ticket_id' => 'strip_tags|trim',
            'title' => 'strip_tags|trim',
            'status' => 'strip_tags|trim',
            'sign_by' => 'strip_tags|trim',
        ];
    }
}