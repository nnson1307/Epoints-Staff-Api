<?php
/**
 * Created by PhpStorm
 * User: Mr Son
 * Date: 9/16/2020
 * Time: 5:21 PM
 */

namespace Modules\Service\Http\Requests\Service;


use MyCore\Http\Request\BaseFormRequest;

class LikeUnLikeRequest extends BaseFormRequest
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
            'service_code' => 'required',
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
            'service_code.required' => __('Hãy nhập mã dịch vụ.'),
            'type.required' => __('Hãy nhập loại thích hoặc không thích.')
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
            'type' => 'strip_tags|trim',
            'service_code' => 'strip_tags|trim',
        ];
    }
}