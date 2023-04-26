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
class WorkAddRequest extends BaseFormRequest
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
            'manage_work_title'    => 'required|max:255',
            'manage_type_work_id'    => 'required',
            'to_date'    => 'required',
            'processor_id'    => 'required',
//            'manage_project_id'    => 'required',
//            'type_card_work'    => 'required',
            'priority'    => 'required',
            'manage_status_id'    => 'required',
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
            'manage_work_title.required' => __('Vui lòng nhập tên công việc'),
            'manage_work_title.max' => __('Tên công việc vượt quá 255 ký tự'),
            'manage_type_work_id.required' => __('Vui lòng chọn nhóm công việc'),
            'to_date.required' => __('Vui lòng chọn hạn công việc'),
            'processor_id.required' => __('Vui lòng chọn người thực hiện'),
//            'type_card_work.required' => __('Vui lòng chọn loại thẻ dịch vụ'),
            'priority.required' => __('Vui lòng chọn tiến độ dự án'),
            'manage_status_id.required' => __('Vui lòng chọn trạng thái'),
            'manage_project_id.required' => __('Vui lòng chọn dự án'),
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