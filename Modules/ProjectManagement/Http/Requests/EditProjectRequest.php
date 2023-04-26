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

class EditProjectRequest extends BaseFormRequest
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
            'project_id'=> 'integer|required',//
            'project_name'=> 'required|unique:manage_project,manage_project_name,'.$params['project_id'].',manage_project_id',
//            'project_describe' => 'required',
            'project_status_id' => 'integer|required',//
//            'date_end' => 'required',
//            'manager_id' => 'integer|required',//
//            'department_id' => 'integer|required',//
            'permission' => 'required',//
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
            'project_id.required' => __('ID dự án không được để trống.'),
            'project_id.integer' => __('ID dự án không đúng kiểu dữ liệu.'),
            'project_name.required'     => __('Tên dự án không được để trống.'),
            'project_name.unique'     => __('Tên dự án đã tồn tại.'),
            'project_status_id.required'     => __('Trạng thái dự án không được để trống.'),
            'project_status_id.integer'     => __('ID trạng thái dự án không đúng kiểu dữ liệu.'),
//            'manager_id.integer'     => __('ID người quản trị không đúng kiểu dữ liệu.'),
//            'manager_id.required'     => __('ID người quản trị không được để trống.'),
//            'department_id.integer'     => __('ID phòng ban không đúng kiểu dữ liệu.'),
//            'department_id.required'     => __('ID phòng ban không được để trống.'),
            'permission.required'     => __('Quyền truy cập không được để trống.'),

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