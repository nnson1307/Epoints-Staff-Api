<?php
/**
 * Created by PhpStorm   .
 * User: Mr Son
 * Date: 2020-01-06
 * Time: 10:34 AM
 * @author SonDepTrai
 */

namespace Modules\Customer\Http\Requests\Customer;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use MyCore\Http\Request\BaseFormRequest;

class UpdateRequest extends BaseFormRequest
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
        $param = request()->all();

        return [
            'customer_id' => 'required',
            'customer_group_id' => 'required',
            'full_name'      => 'required|max:50',
            'phone'         => 'required|digits:10', //|phone|unique:customers,phone1,' .$param['customer_id']. ',customer_id,is_deleted,0
            'province_id' => 'required',
            'district_id' => 'required',
            'ward_id' => 'required',
            'address' => 'required'
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
            'customer_id.required'    => __('Mã khách hàng là thông tin bắt buộc'),
            'customer_group_id.required'    => __('Nhóm khách hàng là thông tin bắt buộc'),
            'full_name.required'    => __('Họ tên là thông tin bắt buộc'),
            'full_name.max'    => __('Họ tên tối đa 50 kí tự'),
            'phone.required'       => __('Số điện thoại là thông tin bắt buộc'),
            'phone.digits'         => __('Số điện thoại không đúng định dạng'),
            'phone.phone'          => __('Số điện thoại không đúng định dạng'),
            'phone.unique'          => __('Số điện thoại đã tồn tại'),
            'province_id.required' => __('Tỉnh thành không được trống.'),
            'district_id.required' => __('Quận huyện không được trống.'),
            'ward_id.required' => __('Phường xã không được trống.'),
            'address.required' => __('Địa chỉ không được trống.'),
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
            'customer_id' => 'strip_tags|trim',
            'customer_group_id' => 'strip_tags|trim',
            'phone'     => 'strip_tags|trim',
            'full_name'  => 'strip_tags|trim',
            'province_id'  => 'strip_tags|trim',
            'district_id'  => 'strip_tags|trim',
            'address'  => 'strip_tags|trim',
        ];
    }
}