<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 24/05/2021
 * Time: 14:55
 */

namespace Modules\Customer\Http\Requests\CustomerContact;

use MyCore\Http\Request\BaseFormRequest;

class RemoveRequest extends BaseFormRequest
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
            'customer_contact_id' => 'required|integer'
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
            'customer_contact_id.required' => __('Mã địa chỉ không được trống.'),
            'customer_contact_id.integer' => __('Kiểu dữ liệu không hợp lệ.')
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
            'customer_contact_id' => 'strip_tags|trim',
        ];
    }
}