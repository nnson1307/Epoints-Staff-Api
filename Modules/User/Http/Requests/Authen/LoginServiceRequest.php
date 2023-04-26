<?php
/**
 * Created by PhpStorm   .
 * User: Mr Son
 * Date: 2020-02-28
 * Time: 10:45 AM
 * @author SonDepTrai
 */

namespace Modules\User\Http\Requests\Authen;


use MyCore\Http\Request\BaseFormRequest;

class LoginServiceRequest extends BaseFormRequest
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
            'object_type' => 'required',
            'object_id'     => 'required',
            'full_name' => 'max:180',
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
            'object_type.required'    => __('Hãy nhập hình thức đăng nhập'),
            'object_id.required'         => __('Hãy nhập object_id'),
            'full_name.max' => __('Tên khách hàng tối đa 180 kí tự.'),
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
            'full_name'    => 'strip_tags|trim',
            'FbId' => 'strip_tags|trim',
        ];
    }
}