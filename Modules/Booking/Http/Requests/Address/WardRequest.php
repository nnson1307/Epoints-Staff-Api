<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 25/08/2022
 * Time: 13:38
 */

namespace Modules\Booking\Http\Requests\Address;

use MyCore\Http\Request\BaseFormRequest;

class WardRequest extends BaseFormRequest
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
            'district_id' => 'required'
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
            'district_id.required'     => __('Mã quận huyện không được trống.')
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