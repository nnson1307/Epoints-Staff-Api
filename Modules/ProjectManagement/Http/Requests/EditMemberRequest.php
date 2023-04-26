<?php


namespace Modules\ProjectManagement\Http\Requests;

use MyCore\Http\Request\BaseFormRequest;
class EditMemberRequest extends BaseFormRequest
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
            'manage_project_id' => 'integer|required',
            'manage_project_staff_id' => 'required',
            'manage_project_role_id' => 'integer|required',
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
            'manage_project_id.required'     => __('Nhập id dự án.'),
            'manage_project_id.integer'     => __('Id dự án không đúng kiểu dữ liệu.'),
            'manage_project_staff_id.required'     => __('Vui lòng nhập ID thành viên.'),
            'manage_project_role_id.required'     => __('Vui lòng nhập vai trò.'),
            'manage_project_role_id.integer'     => __('Vai trò không đúng kiểu dữ liệu.'),

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

        ];
    }
}