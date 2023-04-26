<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 24/05/2021
 * Time: 16:39
 */

namespace Modules\TimeOffDays\Http\Requests\RequestForm;

use MyCore\Http\Request\BaseFormRequest;

class TimeOffDaysActivityRequest extends BaseFormRequest
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
            'time_off_days_id'  => 'required',
            'is_approvce'       => 'required',
            // 'time_off_days_activity_approve_note' => 'required',
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
            'time_off_days_id.required' => __('ID nghỉ phép không được trống.'),
            'is_approvce.required'      => __('Trạng thái không được trống.'),
            // 'time_off_days_activity_approve_note.required' => __('Ghi chú không được trống.'),
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