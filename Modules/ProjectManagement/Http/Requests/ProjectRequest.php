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

class ProjectRequest extends BaseFormRequest
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
            'project_name'=> 'required|unique:manage_project,manage_project_name',
            'project_describe' => 'required',
            'project_status_id' => 'integer|required',
            'date_start' => 'required',
            'date_end' => 'required',
//            'manager_id' => 'integer|required',
//            'department_id' => 'integer|required',
//            'prefix_code' => 'required|unique:manage_project,prefix_code',
            'customer_type' => 'nullable',
            'customer_id' => 'integer|nullable',
            'permission' => 'required',
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
            'project_name.required'     => __('Tên dự án không được để trống.'),
            'project_name.unique'     => __('Tên dự án đã tồn tại.'),
            'project_describe.required'     => __('Mô tả dự án không được để trống.'),
            'project_status_id.required'     => __('Trạng thái dự án không được để trống.'),
            'project_status_id.integer'     => __('ID trạng thái dự án không đúng kiểu dữ liệu.'),
            'date_start.required'     => __('Vui lòng nhập ngày bắt đầu dự án.'),
            'date_end.required'     => __('Vui lòng nhập ngày kết thúc dự án.'),
//            'manager_id.integer'     => __('ID người quản trị không đúng kiểu dữ liệu.'),
//            'manager_id.required'     => __('ID người quản trị không được để trống.'),
//            'department_id.integer'     => __('ID phòng ban không đúng kiểu dữ liệu.'),
//            'department_id.required'     => __('ID phòng ban không được để trống.'),
//            'prefix_code.required'     => __('Tiền tố công việc không được để trống.'),
//            'prefix_code.unique'     => __('Tiền tố công việc đã tồn tại.'),
            'customer_type.nullable'     => __('Vui lòng nhập loại khách hàng.'),
            'customer_id.nullable'     => __('Vui lòng nhập ID khách hàng.'),
            'customer_id.integer'     => __('ID khách hàng không đúng kiểu dữ liệu.'),
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