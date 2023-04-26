<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 28/09/2022
 * Time: 11:09
 */

namespace Modules\Chat\Http\Requests;

use MyCore\Http\Request\BaseFormRequest;
use Illuminate\Validation\Rule;

class GetStaffChatRequest extends BaseFormRequest
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
//            'platform' => [
//                'required',
//                Rule::in(['web', 'app'])
//            ],
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
            'platform.required' => __('Platform là thông tin bắt buộc'),
            'platform.in' => __('Platform không đúng. Platform phải là web hoặc app'),
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
            'platform'     => 'strip_tags|trim'
        ];
    }
}