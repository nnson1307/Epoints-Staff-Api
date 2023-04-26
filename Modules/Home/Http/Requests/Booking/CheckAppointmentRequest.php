<?php
/**
 * Created by PhpStorm   .
 * User: Mr Son
 * Date: 2020-02-18
 * Time: 11:36 AM
 * @author SonDepTrai
 */

namespace Modules\Home\Http\Requests\Booking;


use MyCore\Http\Request\BaseFormRequest;

class CheckAppointmentRequest extends BaseFormRequest
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
            'date' => 'required|date_format:d/m/Y',
            'branch_id' => 'integer|required',
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
            'date.required' => __('Ngày đặt lịch không được trống.'),
            'date.date_format' => __('Kiểu dữ liệu không hợp lệ.'),
            'branch_id.integer'     => __('Kiểu dữ liệu không hợp lệ.'),
            'branch_id.required' => __('Hãy chọn chi nhánh.'),
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