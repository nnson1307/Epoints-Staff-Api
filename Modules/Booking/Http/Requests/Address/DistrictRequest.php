<?php
/**
 * Created by PhpStorm   .
 * User: Mr Son
 * Date: 2020-01-06
 * Time: 2:27 PM
 * @author SonDepTrai
 */

namespace Modules\Booking\Http\Requests\Address;


use MyCore\Http\Request\BaseFormRequest;

class DistrictRequest extends BaseFormRequest
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
            'provinceid' => 'required'
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
//            'provinceid.integer'     => __('Kiểu dữ liệu không hợp lệ.'),
            'provinceid.required'     => __('Mã tỉnh thành không được trống.')
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