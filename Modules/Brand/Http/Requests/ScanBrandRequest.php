<?php
/**
 * User: HIEUPC
 * Date: 2022-10-17
 */

namespace Modules\Brand\Http\Requests;


use MyCore\Http\Request\BaseFormRequest;

class ScanBrandRequest extends BaseFormRequest
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
            "brand_customer_code" => "required",
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
            'brand_customer_code.required' => __('Vui lòng nhập mã thương hiệu'),
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
            'brand_customer_code' => 'strip_tags|trim',
        ];
    }
}