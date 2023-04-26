<?php

namespace Modules\CustomerLead\Http\Requests\CustomerLead;

use MyCore\Http\Request\BaseFormRequest;
use Illuminate\Validation\Rule;

/**
 * Class LoginRequest
 * @package Modules\User\Http\Requests\Authen
 * @author DaiDP
 * @since Aug, 2019
 */
class CreateCommentRequest extends BaseFormRequest
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
            'customer_lead_id'    => 'required',
            'message'       => 'required',
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
            'customer_lead_id.required' => __('Vui lòng truyền id khách hàng'),
            'message.required' => __('Vui lòng truyền nội dung'),
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