<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 26/08/2022
 * Time: 15:44
 */

namespace Modules\Booking\Http\Requests\Booking;

use MyCore\Http\Request\BaseFormRequest;

class ListDayWeekMonthRequest extends BaseFormRequest
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
            'view_type' => 'required',
            'date_start' => 'required'
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
            'view_type.required' => __('Hãy nhập kiểu xem lịch hẹn.'),
            'date_start.required' => __('Hãy nhập ngày bắt đầu.')
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
            'view_type'     => 'strip_tags|trim',
            'date_start'     => 'strip_tags|trim',
            'date_end'     => 'strip_tags|trim'
        ];
    }
}