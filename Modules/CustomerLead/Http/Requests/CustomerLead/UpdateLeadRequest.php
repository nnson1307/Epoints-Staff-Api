<?php
/**
 * Created by PhpStorm   .
 * User: Mr To
 * Date: 2022-09-13
 * Time: 3:00 PM
 * @author doanhongto
 */

namespace Modules\CustomerLead\Http\Requests\CustomerLead;


use MyCore\Http\Request\BaseFormRequest;

class UpdateLeadRequest extends BaseFormRequest
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
            'customer_lead_code' => 'required',
            'avatar'=> 'string|nullable',
            'customer_type' => 'required',//
            'full_name' => 'required',//
            'phone' => 'string|required|unique:cpo_customer_lead,phone,'.$params['customer_lead_code'].',customer_lead_code,is_deleted,0',//
            'customer_source'=>'integer|required',//
            'pipeline_code'=> 'required',//
            'journey_code'=> 'required',
            'address'=> 'nullable',//
            'province_id'=> 'integer|nullable',
            'district_id'=> 'integer|nullable',
            'ward_id'=> 'integer|nullable',
            'sale_id'=> 'integer|nullable',
            'bussiness_id'=> 'integer|nullable',
            'birthday'=> 'nullable|before:today',
            'email' => 'nullable|email'
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
            'customer_lead_code.required'     => __('Nhập mã khách hàng.'),
            'avatar.string'     => __('Avatar khách hàng không đúng định dạng.'),
            'avatar.nullable'     => __('Nhập link avatar khách hàng.'),
            'customer_type.required'     => __('Loại khách hàng không được để trống.'),
            'full_name.required'     => __('Tên khách hàng không được để trống.'),
            'phone.string'     => __('Số điện thoại khách hàng không đúng kiểu dữ liệu.'),
            'phone.required'     => __('Số điện thoại Khách hàng khộng được để trống.'),
            'phone.unique'     => __('Số điện thoại Khách hàng đã tồn tại.'),
            'customer_source.integer'     => __('Loại khách hàng không đúng định dạng.'),
            'customer_source.required'     => __('Nguồn khách hàng không được để trống.'),
            'pipeline_code.required'     => __('Mã pipeline không được để trống.'),
            'journey_code.required'     => __('Trạng thái hành trình không được để trống.'),
            'address.nullable'     => __('Vui lòng nhập Địa chỉ Khách hàng.'),
            'province_id.integer'     => __('ID Tỉnh(thành phố) không đúng định dạng.'),
            'district_id.integer'     => __('ID Quận(huyện) không đúng định dạng.'),
            'ward_id.integer'     => __('ID Phường(xã) không đúng định dạng.'),
            'bussiness_id.integer'     => __('Mã ngành nghề kinh doanh không đúng kiểu dữ liệu.'),
            'birthday.before'     => __('Ngày sinh phải trước hôm nay.'),
            'email.email' => __('Email không hợp lệ'),
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