<?php
/**
 * Created by PhpStorm
 * User: Mr Son
 * Date: 9/17/2020
 * Time: 11:22 AM
 */

namespace Modules\Service\Http\Requests\Service;


use MyCore\Http\Request\BaseFormRequest;

class ServiceLikeListRequest extends BaseFormRequest
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
            'page' => 'integer'
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
            'page.integer'     => __('Kiểu dữ liệu không hợp lệ.'),
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
            'page'     => 'strip_tags|trim'
        ];
    }
}