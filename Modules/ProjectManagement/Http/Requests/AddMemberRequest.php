<?php
/**
 * Created by PhpStorm   .
 * User: Mr To
 * Date: 2022-09-13
 * Time: 3:00 PM
 * @author doanhongto
 */

namespace Modules\ProjectManagement\Http\Requests;


use MyCore\Http\Request\BaseFormRequest;

class AddMemberRequest extends BaseFormRequest
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
        $params = request()->all();
        return [
            'manage_project_id' => 'integer|required',
            'staff_id'=> 'required',
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
              'manage_project_id.required'  => __('Nhập ID dự án'),
              'manage_project_id.integer'  => __('ID dự án không đúng kiểu dữ liệu'),
              'staff_id.required'  => __('Nhập ID nhân viên'),
              'manage_project_role_id.required'  => __('Nhập ID chức vụ'),
              'manage_project_role_id.integer'  => __('ID chức vụ không đúng kiểu dữ liệu'),
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