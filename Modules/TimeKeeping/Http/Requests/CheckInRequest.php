<?php

namespace Modules\TimeKeeping\Http\Requests;

use MyCore\Http\Request\BaseFormRequest;

/**
 * Class LoginRequest
 * @package Modules\User\Http\Requests\Authen
 * @author DaiDP
 * @since Aug, 2019
 */
class CheckInRequest extends BaseFormRequest
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
            'time_working_staff_id'    => 'required',
//            'device_id'    => 'string',
//            'access_point_ip'    => 'required',
//            'check_sum'    => 'string',
//            'wifi_name'    => 'string',
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
            'time_working_staff_id.required' => __('Ca làm việc là thông tin bắt buộc.'),
            'access_point_ip.required' => __('Địa chỉ IP wifi là thông tin bắt buộc.')
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
            'time_working_staff_id' => 'strip_tags|trim',
            'device_id' => 'strip_tags|trim',
            'access_point_ip' => 'strip_tags|trim',
            'check_sum' => 'strip_tags|trim',
            'wifi_name' => 'strip_tags|trim',
        ];
    }
}