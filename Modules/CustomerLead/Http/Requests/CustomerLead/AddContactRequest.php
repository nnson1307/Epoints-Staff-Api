<?php
/**
 * Created by PhpStorm   .
 * User: Mr To
 * Date: 2022-09-13
 * Time: 3:00 PM
 * @author doanhongto
 */

namespace Modules\CustomerLead\Http\Requests\CustomerLead;


use AWS\CRT\HTTP\Request;
use Illuminate\Validation\Rule;
use MyCore\Http\Request\BaseFormRequest;

class AddContactRequest extends BaseFormRequest
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
            'customer_lead_code' => 'required',
            'full_name' => 'required',
            'phone' => 'string|required|unique:cpo_customer_contact,phone',
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
            'customer_lead_code.required'     => __('Mã Khách hàng tiềm năng không được để trống.'),
            'full_name.required'     => __('Tên người liên hệ không được để trống.'),
            'phone.string'     => __('Số điện thoại không đúng kiểu dữ liệu.'),
            'phone.required'     => __('Số điện thoại người liên hệ không được để trống.'),
            'phone.unique'     => __('Số điện thoại người liên hệ đã tồn tại.'),

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

        ];
    }
}