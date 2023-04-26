<?php
/**
 * Created by PhpStorm
 * User: Mr Son
 * Date: 10/2/2020
 * Time: 1:57 PM
 */

namespace Modules\Booking\Http\Requests\Booking;


use MyCore\Http\Request\BaseFormRequest;

class ReBookingRequest extends BaseFormRequest
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
            'customer_appointment_code' => 'required',
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
            'customer_appointment_code.required'     => __('Hãy nhập mã lịch hẹn.'),
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
            'customer_appointment_code'     => 'strip_tags|trim',
        ];
    }
}