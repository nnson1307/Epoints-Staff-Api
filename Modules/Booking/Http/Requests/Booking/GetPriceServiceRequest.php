<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 12/04/2021
 * Time: 16:38
 */

namespace Modules\Booking\Http\Requests\Booking;


use MyCore\Http\Request\BaseFormRequest;

class GetPriceServiceRequest extends BaseFormRequest
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
            'time' => 'required|date_format:H:i',
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
            'time.required' => __('Thời gian đặt lịch không được trống.'),
            'time.date_format' => __('Kiểu dữ liệu không hợp lệ.')
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