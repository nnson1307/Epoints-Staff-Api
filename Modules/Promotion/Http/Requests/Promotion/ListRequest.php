<?php
/**
 * Created by PhpStorm
 * User: Mr Son
 * Date: 9/14/2020
 * Time: 10:44 AM
 */

namespace Modules\Promotion\Http\Requests\Promotion;


use MyCore\Http\Request\BaseFormRequest;

class ListRequest extends BaseFormRequest
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
            'keyword' => 'nullable',
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
            'page.integer'  => __('Kiểu dữ liệu không hợp lệ.'),
            'type.required' => __('Hãy nhập loại CTKM.')
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
            'page' => 'strip_tags|trim',
            'keyword' => 'strip_tags|trim',
            'type' => 'strip_tags|trim',
        ];
    }
}