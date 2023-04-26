<?php
/**
 * Created by PhpStorm
 * User: Mr Son
 * Date: 10/5/2020
 * Time: 5:07 PM
 */

namespace Modules\Order\Http\Requests\Order;


use MyCore\Http\Request\BaseFormRequest;

class CheckInventoryRequest extends BaseFormRequest
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
            'branch_code' => 'nullable',
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
            'branch_code.required'     => __('Mã chi nhánh không được trống.'),
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
            'branch_code'     => 'strip_tags|trim',
        ];
    }
}