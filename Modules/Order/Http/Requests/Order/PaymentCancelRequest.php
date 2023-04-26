<?php
/**
 * Created by PhpStorm
 * User: Mr Son
 * Date: 10/10/2020
 * Time: 5:19 PM
 */

namespace Modules\Order\Http\Requests\Order;


use MyCore\Http\Request\BaseFormRequest;

class PaymentCancelRequest extends BaseFormRequest
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
            'AccessCode' => 'required',
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
            'AccessCode.required'     => __('Hãy nhập AccessCode.'),
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
            'AccessCode'     => 'strip_tags|trim'
        ];
    }
}