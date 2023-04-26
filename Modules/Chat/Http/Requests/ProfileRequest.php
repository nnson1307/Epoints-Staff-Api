<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 10/06/2022
 * Time: 10:13
 */

namespace Modules\Chat\Http\Requests;

use MyCore\Http\Request\BaseFormRequest;

class ProfileRequest extends BaseFormRequest
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
            'token'  => 'required',
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
            'token.required'      => __('Token là thông tin bắt buộc')
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