<?php
/**
 * Created by PhpStorm
 * User: Mr Son
 * Date: 11/9/2020
 * Time: 10:49 AM
 */

namespace Modules\Home\Http\Requests\Home;


use MyCore\Http\Request\BaseFormRequest;

class SearchRequest extends BaseFormRequest
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
            'keyword' => 'nullable'
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
//            'keyword.integer'     => __('Kiểu dữ liệu không hợp lệ.'),
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
            'keyword'     => 'strip_tags|trim',
        ];
    }
}