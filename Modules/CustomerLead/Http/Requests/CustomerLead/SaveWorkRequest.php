<?php
/**
 * Created by PhpStorm   .
 * User: Mr To
 * Date: 2022-09-13
 * Time: 3:00 PM
 * @author doanhongto
 */

namespace Modules\CustomerLead\Http\Requests\CustomerLead;


use Illuminate\Validation\Rule;
use MyCore\Http\Request\BaseFormRequest;

class SaveWorkRequest extends BaseFormRequest
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
            'manage_work_customer_type'=> [
                'required',
                Rule::in(['customer', 'lead'])
            ],
            'manage_work_title'=> 'string|required',
            'manage_type_work_id' => 'integer|required',
            'description' => 'required',
            'created_by'=>'integer|required',
            'date_start'=>'required',
            'date_end'=>'required',
            'manage_status_id'=>'integer|required',

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
            'manage_work_customer_type.required'     => __('Loại khách hàng không được để trống.'),
            'manage_work_customer_type.in' => __('Loại khách hàng không đúng. Loại khách hàng phải là customer hoặc lead'),
            'manage_work_title.string'     => __('Tiêu đề công việc không đúng kiểu dữ liệu.'),
            'manage_work_title.required'     => __('Tiêu đề công việc không được để trống.'),
            'manage_type_work_id.integer'     => __('ID loại công việc không đúng kiểu dữ liệu.'),
            'manage_type_work_id.required'     => __('ID loại công việc không được để trống.'),
            'crated_by.integer'     => __('ID người thực hiện không đúng kiểu dữ liệu.'),
            'crated_by.required'     => __('ID người thực hiện không được để trống.'),
            'date_start.required'     => __('Ngày bắt đầu không được để trống.'),
            'date_end.required'     => __('Ngày kết thúc không được để trống.'),
            'manage_status_id.required'     => __('Trạng thái công việc không được để trống.'),
            'manage_status_id.integer'     => __('Trạng thái công việc không đúng kiểu dữ liệu.'),
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