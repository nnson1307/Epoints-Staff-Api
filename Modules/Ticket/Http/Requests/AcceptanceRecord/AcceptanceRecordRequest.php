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
class AcceptanceRecordRequest extends BaseFormRequest
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
            'title'    => 'required|max:191',
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
            'ticket_id.required' => __('Id ticket là thông tin bắt buộc'),
            'title.required' => __('Tên biên bản nghiệm thu là thông tin bắt buộc'),
            'title.max' => __('Tên biên bản nghiệm thu vượt quá 191 ký tự'),
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
        ];
    }
}