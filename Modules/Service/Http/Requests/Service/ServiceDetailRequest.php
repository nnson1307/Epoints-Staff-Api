<?php
/**
 * Created by PhpStorm
 * User: Mr Son
 * Date: 13-04-02020
 * Time: 11:54 AM
 */

namespace Modules\Service\Http\Requests\Service;


use MyCore\Http\Request\BaseFormRequest;

class ServiceDetailRequest extends BaseFormRequest
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
            'service_id' => 'integer|required',
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
            'service_id.integer'     => __('Kiểu dữ liệu không hợp lệ.'),
            'service_id.required' => __('Hãy nhập mã dịch vụ.'),
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
            'service_id' => 'strip_tags|trim',
        ];
    }
}