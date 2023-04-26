<?php
/**
 * Created by PhpStorm
 * User: Mr Son
 * Date: 13-04-02020
 * Time: 11:18 AM
 */

namespace Modules\Service\Http\Requests\Service;


use MyCore\Http\Request\BaseFormRequest;

class ServiceRepresentativeListRequest extends BaseFormRequest
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
            'service_category_id.integer'     => __('Kiểu dữ liệu không hợp lệ.'),
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
            'sort_type' => 'strip_tags|trim',
            'type' => 'strip_tags|trim'
        ];
    }
}