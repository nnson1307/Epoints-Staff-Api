<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 28/09/2022
 * Time: 14:27
 */

namespace Modules\ManageWork\Http\Requests\Work;

use MyCore\Http\Request\BaseFormRequest;

class CreateLocationRequest extends BaseFormRequest
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
            'manage_work_id'    => 'required',
            'lat'    => 'required',
            'lng'    => 'required',
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
            'manage_work_id.required' => __('Hãy chọn công việc'),
            'lat.required' => __('Hãy nhập vĩ độ'),
            'lng.required' => __('Hãy nhập kinh độ'),
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
            'manage_work_id' => 'strip_tags|trim',
            'lat' => 'strip_tags|trim',
            'lng' => 'strip_tags|trim',
            'description' => 'strip_tags|trim',
        ];
    }
}