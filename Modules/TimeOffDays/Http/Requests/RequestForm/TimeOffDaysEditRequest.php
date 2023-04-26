<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 24/05/2021
 * Time: 16:39
 */

namespace Modules\TimeOffDays\Http\Requests\RequestForm;

use MyCore\Http\Request\BaseFormRequest;

class TimeOffDaysEditRequest extends BaseFormRequest
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
            'time_off_days_id'      => 'required',
            'time_off_type_id'      => 'required',
            'time_off_days_start'   => 'required',
            'time_off_days_end'     => 'required',
            'time_off_note'         => 'required',
            'time_off_days_time'    => 'nullable',
            'time_off_days_shift'         => 'required',
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
            'time_off_days_id.required' => __('Id đơn nghỉ phép không được trống.'),
            'time_off_type_id.required' => __('Loại đơn nghỉ phép không được trống.'),
            'time_off_days_start.required' => __('Ngày bắt đầu không được trống.'),
            'time_off_days_end.required' => __('Ngày kết thúc không được trống.'),
            'time_off_note.required' => __('Ghi chú không được trống.'),
            'time_off_days_shift.required' => __('Ca làm việc không được trống.'),
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

        ];
    }
}