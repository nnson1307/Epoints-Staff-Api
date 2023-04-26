<?php
/**
 * Created by PhpStorm   .
 * User: Mr tungnt
 * Date: 2020-08-10
 * Time: 2:15 PM
 * @author tungnui
 */

namespace Modules\Home\Http\Requests\Home;


use MyCore\Http\Request\BaseFormRequest;

class HomeRequest extends BaseFormRequest
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
            'banner_id' => 'integer|required',
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
            'banner_id.integer'     => __('Kiểu dữ liệu không hợp lệ.'),
            'banner_id.required' => __('Hãy chọn chi nhánh.'),
            'date.required' => __('Ngày đặt lịch không được trống.'),
            'date.date_format' => __('Kiểu dữ liệu không hợp lệ.'),
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