<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 30/08/2022
 * Time: 14:44
 */

namespace Modules\Booking\Http\Requests\Booking;

use MyCore\Http\Request\BaseFormRequest;

class ListRangeTimeRequest extends BaseFormRequest
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
            'date' => 'required'
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
            'date.required' => __('Hãy nhập ngày hẹn.')
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
            'date'     => 'strip_tags|trim',
            'time_start'     => 'strip_tags|trim',
            'time_end'     => 'strip_tags|trim'
        ];
    }
}