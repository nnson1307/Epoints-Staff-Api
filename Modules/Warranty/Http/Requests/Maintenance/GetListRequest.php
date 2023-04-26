<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 27/09/2022
 * Time: 14:56
 */

namespace Modules\Warranty\Http\Requests\Maintenance;

use MyCore\Http\Request\BaseFormRequest;

class GetListRequest extends BaseFormRequest
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
            'page' => 'strip_tags|trim',
            'search' => 'strip_tags|trim',
            'packed_code' => 'strip_tags|trim',
            'warranty_card_code' => 'strip_tags|trim',
            'created_at' => 'strip_tags|trim',
        ];
    }
}