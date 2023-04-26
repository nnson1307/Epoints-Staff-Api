<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 06/05/2021
 * Time: 14:55
 */

namespace Modules\User\Http\Requests\Brand;


use MyCore\Http\Request\BaseFormRequest;

class GetBrandRequest extends BaseFormRequest
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
            'client_key'    => 'required',
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
            'client_key.required' => __('Client key là thông tin bắt buộc'),
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
            'client_key' => 'strip_tags|trim',
        ];
    }
}