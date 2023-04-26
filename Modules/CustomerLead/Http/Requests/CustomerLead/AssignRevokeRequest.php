<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 13/10/2022
 * Time: 11:14
 */

namespace Modules\CustomerLead\Http\Requests\CustomerLead;

use MyCore\Http\Request\BaseFormRequest;
use Illuminate\Validation\Rule;

class AssignRevokeRequest extends BaseFormRequest
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
            'type' => [
                'required',
                Rule::in(['assign', 'revoke'])
            ],
            'customer_lead_code' => 'required',
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
            'type.required'     => __('Nhập loại.'),
            'type.in' => __('Loại không đúng. Platform phải là assign hoặc revoke'),
            'customer_lead_code.required'     => __('Nhập mã khách hàng.')
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
            'type' => 'strip_tags|trim',
            'customer_lead_code' => 'strip_tags|trim',
            'sale_id' => 'strip_tags|trim',
            'time_revoke_lead' => 'strip_tags|trim',
        ];
    }
}