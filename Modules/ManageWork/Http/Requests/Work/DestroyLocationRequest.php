<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 30/09/2022
 * Time: 15:44
 */

namespace Modules\ManageWork\Http\Requests\Work;

use MyCore\Http\Request\BaseFormRequest;

class DestroyLocationRequest extends BaseFormRequest
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
            'manage_work_location_id'    => 'required'
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
            'manage_work_location_id.required' => __('Hãy nhập mã toạ độ')
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
            'manage_work_location_id' => 'strip_tags|trim'
        ];
    }
}