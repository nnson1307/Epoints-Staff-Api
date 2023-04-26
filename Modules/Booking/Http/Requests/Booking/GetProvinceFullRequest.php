<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 11/05/2021
 * Time: 10:46
 */

namespace Modules\Booking\Http\Requests\Booking;

use MyCore\Http\Request\BaseFormRequest;

class GetProvinceFullRequest extends BaseFormRequest
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