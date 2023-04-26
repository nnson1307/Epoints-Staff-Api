<?php
/**
 * Created by PhpStorm   .
 * User: Mr Son
 * Date: 2020-03-24
 * Time: 3:00 PM
 * @author SonDepTrai
 */

namespace Modules\Booking\Http\Requests\Booking;


use MyCore\Http\Request\BaseFormRequest;

class BookingHistoryDetailRequest extends BaseFormRequest
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
            'customer_appointment_id' => 'integer|required',
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
            'customer_appointment_id.integer'     => __('Kiểu dữ liệu không hợp lệ.'),
            'customer_appointment_id.required'     => __('Mã lịch hẹn không được trống.'),
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