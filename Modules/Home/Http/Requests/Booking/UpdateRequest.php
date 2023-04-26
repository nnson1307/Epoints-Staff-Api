<?php
/**
 * Created by PhpStorm   .
 * User: Mr Son
 * Date: 2020-02-19
 * Time: 9:00 AM
 * @author SonDepTrai
 */

namespace Modules\Home\Http\Requests\Booking;


use MyCore\Http\Request\BaseFormRequest;

class UpdateRequest extends BaseFormRequest
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
            'branch_id' => 'integer|required',
            'date' => 'required|date_format:d/m/Y',
            'time' => 'required|date_format:H:i',
            'staff_id' => 'integer|nullable'
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
            'branch_id.integer'     => __('Kiểu dữ liệu không hợp lệ.'),
            'branch_id.required' => __('Hãy chọn chi nhánh.'),
            'date.required' => __('Ngày đặt lịch không được trống.'),
            'date.date_format' => __('Kiểu dữ liệu không hợp lệ.'),
            'time.required' => __('Thời gian đặt lịch không được trống.'),
            'time.date_format' => __('Kiểu dữ liệu không hợp lệ.'),
            'staff_id.integer' => __('Kiểu dữ liệu không hợp lệ.')
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