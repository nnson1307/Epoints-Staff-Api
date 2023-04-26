<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 27/09/2022
 * Time: 11:23
 */

namespace Modules\Warranty\Http\Requests\WarrantyCard;

use MyCore\Http\Request\BaseFormRequest;

class UpdateRequest extends BaseFormRequest
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
            'warranty_card_code' => 'required',
            'status' => 'required',
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
            'warranty_card_code.required'     => __('Hãy nhập mã phiếu bảo hành'),
            'status.required'     => __('Hãy chọn trạng thái'),
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
            'warranty_card_code' => 'strip_tags|trim',
            'status' => 'strip_tags|trim',
            'object_serial' => 'strip_tags|trim',
            'description' => 'strip_tags|trim'
        ];
    }
}