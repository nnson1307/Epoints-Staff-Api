<?php

namespace Modules\ManageWork\Http\Requests\Work;

use MyCore\Http\Request\BaseFormRequest;
use Illuminate\Validation\Rule;

/**
 * Class LoginRequest
 * @package Modules\User\Http\Requests\Authen
 * @author DaiDP
 * @since Aug, 2019
 */
class WorkEditRequest extends BaseFormRequest
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
            'processor_id'    => 'required',
            'manage_work_title'    => 'required|max:255',
            'to_date'    => 'required',
//            'manage_project_id'    => 'required',
            'manage_type_work_id'    => 'required',
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
            'manage_work_id.required' => __('manage_work_id không được để trống'),
            'processor_id.required' => __('Vui lòng chọn người thực hiện'),
            'manage_work_title.required' => __('Vui lòng nhập tên công việc'),
            'to_date.required' => __('Vui lòng chọn hạng công việc'),
            'manage_project_id.required' => __('Vui lòng chọn dự án'),
            'manage_type_work_id.required' => __('Vui lòng chọn nhóm công việc'),
            'manage_work_title.max' => __('Tên công việc vượt quá 255 ký tự'),
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
            'manage_work_title' => 'strip_tags|trim',
        ];
    }
}