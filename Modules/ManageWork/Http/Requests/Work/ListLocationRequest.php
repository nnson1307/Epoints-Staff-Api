<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 30/09/2022
 * Time: 14:33
 */

namespace Modules\ManageWork\Http\Requests\Work;

use MyCore\Http\Request\BaseFormRequest;

class ListLocationRequest extends BaseFormRequest
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
            'manage_work_id'    => 'required'
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
            'manage_work_id.required' => __('manage_work_id không được để trống')
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
        ];
    }
}