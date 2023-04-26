<?php
/**
 * Created by PhpStorm   .
 * User: Mr Son
 * Date: 2020-03-24
 * Time: 3:00 PM
 * @author SonDepTrai
 */

namespace Modules\CustomerLead\Http\Requests\CustomerLead;


use MyCore\Http\Request\BaseFormRequest;


class CustomerLeadRequest extends BaseFormRequest
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

}