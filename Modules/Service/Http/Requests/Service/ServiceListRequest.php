<?php
/**
 * Created by PhpStorm   .
 * User: Mr Son
 * Date: 2020-01-09
 * Time: 11:23 AM
 * @author SonDepTrai
 */

namespace Modules\Service\Http\Requests\Service;


use MyCore\Http\Request\BaseFormRequest;

class ServiceListRequest extends BaseFormRequest
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
            'page' => 'integer',
            'sort_type' => 'nullable',
            'service_category_id' => 'integer|nullable'
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
            'service_category_id.integer'     => __('Kiểu dữ liệu không hợp lệ.')
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
            'service_name' => 'strip_tags|trim',
            'page' => 'strip_tags|trim'
        ];
    }
}