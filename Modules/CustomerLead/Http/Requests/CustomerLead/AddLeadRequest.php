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

class AddLeadRequest extends BaseFormRequest
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
            'avatar'=> 'string|nullable',
            'customer_type' => [
                'required',
                Rule::in(['personal', 'business'])
            ],
            'customer_source'=>'integer|required',//
            'full_name' => 'required',//
            'phone' => 'string|required|unique:cpo_customer_lead,phone,' . ',customer_lead_code,is_deleted,0',//
            'email' => 'nullable|email',
            'pipeline_code'=> 'required',//
            'journey_code'=> 'required',//
            'sale_id'=> 'integer|nullable',
            'birthday'=> 'nullable|before:today',
            'bussiness_id'=> 'integer|nullable',
            'province_id'=> 'integer|nullable',
            'district_id'=> 'integer|nullable',
            'ward_id'=> 'integer|nullable',
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
            'avatar.string'     => __('Avatar khách hàng không đúng định dạng.'),
            'customer_type.required'     => __('Loại khách hàng không được để trống.'),
            'customer_type.in'     => __('Loại khách hàng không đúng. Loại khách hàng phải là Cá nhân hoặc Doanh nghiệp'),
            'customer_source.integer'     => __('Loại khách hàng không đúng định dạng.'),
            'customer_source.required'     => __('Nguồn khách hàng không được để trống.'),
            'full_name.required'     => __('Tên doanh nghiệp không được để trống.'),
            'phone.string'     => __('Số điện thoại Khách hàng không đúng kiểu dữ liệu.'),
            'phone.required'     => __('Số điện thoại Khách hàng không được để trống.'),
            'phone.unique'     => __('Số điện thoại Khách hàng đã tồn tại.'),
            'email.email' => __('Email không hợp lệ'),
            'pipeline_code.required'     => __('Mã pipeline không được để trống.'),
            'journey_code.required'     => __('Trạng thái hành trình không được để trống.'),
            'sale_id.integer'     => __('ID người được phân bổ không đúng kiểu dữ liệu.'),
            'birthday.before'     => __('Ngày sinh phải trước hôm nay.'),
            'bussiness_id.integer'     => __('Mã ngành nghề kinh doanh không đúng định dạng.'),
            'province_id.integer'     => __('ID Tỉnh(thành phố) không đúng định dạng.'),
            'district_id.integer'     => __('ID Quận(huyện) không đúng định dạng.'),
            'ward.integer'     => __('ID Phường(xã) không đúng định dạng.'),
            'contact_email.email' => __('Email không hợp lệ'),

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