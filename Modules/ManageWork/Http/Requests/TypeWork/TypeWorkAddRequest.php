<?php

namespace Modules\ManageWork\Http\Requests\TypeWork;

use MyCore\Http\Request\BaseFormRequest;
use Illuminate\Validation\Rule;

/**
 * Class LoginRequest
 * @package Modules\User\Http\Requests\Authen
 * @author DaiDP
 * @since Aug, 2019
 */
class TypeWorkAddRequest extends BaseFormRequest
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
            'manage_type_work_name'    => 'required|max:255',
            'manage_type_work_icon'    => 'required|max:255',
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
            'manage_type_work_name.required' => __('Vui lòng nhập tên loại công việc'),
            'manage_type_work_icon.required' => __('Vui lòng thêm icon'),
            'manage_type_work_name.max' => __('Tên loại công việc vượt quá 255 ký tự'),
            'manage_type_work_icon.max' => __('Tên icon vượt quá 255 ký tự'),
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
            'manage_type_work_name' => 'strip_tags|trim',
        ];
    }
}