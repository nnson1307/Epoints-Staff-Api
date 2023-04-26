<?php

namespace Modules\ManageWork\Http\Requests\Remind;

use MyCore\Http\Request\BaseFormRequest;
use Illuminate\Validation\Rule;

/**
 * Class LoginRequest
 * @package Modules\User\Http\Requests\Authen
 * @author DaiDP
 * @since Aug, 2019
 */
class RemindRequest extends BaseFormRequest
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
            'list_staff'    => 'required',
            'date_remind'    => 'required',
//            'manage_work_id'    => 'required',
            'description'    => 'required',
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
            'list_staff.required' => __('Vui lòng chọn nhân viên được nhắc nhở'),
            'date_remind.required' => __('Vui lòng chọn thời gian nhắc'),
            'manage_work_id.required' => __('Vui lòng chọn công việc'),
            'description.required' => __('Vui lòng nhập nội dung nhắc nhở'),
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
            'list_staff' => 'strip_tags|trim',
            'date_remind' => 'strip_tags|trim',
            'time' => 'strip_tags|trim',
            'time_type' => 'strip_tags|trim',
            'manage_work_id' => 'strip_tags|trim'
        ];
    }
}