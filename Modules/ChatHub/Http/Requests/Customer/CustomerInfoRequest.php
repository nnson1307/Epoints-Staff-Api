<?php
/**
 * User: HIEUPC
 * Date: 2022-10-17
 */

namespace Modules\ChatHub\Http\Requests\Customer;


use MyCore\Http\Request\BaseFormRequest;

class CustomerInfoRequest extends BaseFormRequest
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
            "customer_id" => "required",
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
            'customer_id.required' => __('Hãy nhập mã khách hàng'),
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
            'customer_id' => 'strip_tags|trim',
        ];
    }
}