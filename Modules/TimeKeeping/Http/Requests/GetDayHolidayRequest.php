<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 18/10/2022
 * Time: 11:07
 */

namespace Modules\TimeKeeping\Http\Requests;

use MyCore\Http\Request\BaseFormRequest;

class GetDayHolidayRequest extends BaseFormRequest
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
            'start_date'    => 'required',
            'end_date'    => 'required',
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
            'start_date.required' => __('Hãy nhập ngày bắt đầu.'),
            'end_date.required' => __('Hãy nhập giờ kết thúc.')
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
            'start_date' => 'strip_tags|trim',
            'end_date' => 'strip_tags|trim',
        ];
    }
}