<?php
/**
 * Created by PhpStorm   .
 * User: Mr To
 * Date: 2022-09-13
 * Time: 3:00 PM
 * @author doanhongto
 */

namespace Modules\CustomerLead\Http\Requests\CustomerDeals;


use MyCore\Http\Request\BaseFormRequest;

class UpdateDealRequest extends BaseFormRequest
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
            'deal_code' => 'required',
            'deal_name'=> 'required',
            'phone' => 'required',//
            'sale_id'=> 'integer|required',
            'type_customer' => 'required',//
            'customer_code' => 'required',//
            'pipeline_code'=> 'required',//
            'journey_code'=> 'required',//
//            'closing_date'=> 'date|required',//
            'branch_code'=> 'nullable',
            'tag'=> 'nullable',//
            'order_source_id'=> 'integer|nullable',
            'probability'=> 'nullable',
            'deal_description'=>'nullable'
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
            'deal_code.required'     => __('Mã deal không được để trống.'),
            'deal_name.required'     => __('Tên deal không được để trống.'),
            'type_customer.required'     => __('Loại khách hàng không được để trống.'),
            'customer_code.required'     => __('Mã khách hàng không được để trống.'),
            'phone.integer'     => __('Số điện thoại Khách hàng khộng đúng kiểu dữ liệu.'),
            'phone.required'     => __('Số điện thoại Khách hàng khộng được để trống.'),
            'phone.unique'     => __('Số điện thoại Khách hàng đã tồn tại.'),
            'sale_id.integer'     => __('Mã người được phân bổ không đúng định dạng.'),
            'sale_id.required'     => __('Mã người được phân bổ không được để trống.'),
            'pipeline_code.required'     => __('Mã pipeline không được để trống.'),
            'journey_code.required'     => __('Hành trình không được để trống.'),
//            'closing_date.date'     => __('Ngày kết thúc dự kiến không đúng kiểu dữ liệu.'),
//            'closing_date.required'     => __('Ngày kết thúc dự kiến không được để trống.'),
            'tag.nullable'     => __('Vui lòng nhập ID nhãn.'),
            'order_source_id.nullable'     => __('Vui lòng nhập ID nguồn đơn hàng.'),
            'order_source_id.integer'     => __('ID Nguồn đơn hàng không đúng kiểu dữ liệu.'),
            'probability.integer'     => __('Xác suất không đúng kiểu dữ liệu.'),
            'probability.nullable'     => __('Vui lòng nhập xác suất đơn hàng.'),
            'deal_description.nullable'     => __('Vui lòng nhập chi tiết đơn hàng.'),

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