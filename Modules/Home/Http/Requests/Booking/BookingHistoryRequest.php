<?php
/**
 * Created by PhpStorm   .
 * User: Mr Son
 * Date: 2020-03-24
 * Time: 2:49 PM
 * @author SonDepTrai
 */

namespace Modules\Home\Http\Requests\Booking;


use MyCore\Http\Request\BaseFormRequest;

class BookingHistoryRequest extends BaseFormRequest
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
            'page' => 'integer',
            'branch_id' => 'integer|nullable',
            'type' => 'required'
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
            'page.integer'     => __('Kiểu dữ liệu không hợp lệ.'),
            'branch_id.integer'     => __('Kiểu dữ liệu không hợp lệ.'),
            'type.required' => __('Hãy nhập kiểu lịch hẹn.')
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
            'customer_appointment_code'     => 'strip_tags|trim'
        ];
    }
}