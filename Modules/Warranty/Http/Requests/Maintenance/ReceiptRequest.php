<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 27/09/2022
 * Time: 23:09
 */

namespace Modules\Warranty\Http\Requests\Maintenance;

use MyCore\Http\Request\BaseFormRequest;

class ReceiptRequest extends BaseFormRequest
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
            'maintenance_id' => 'required',
            'maintenance_code' => 'required',
            'customer_id' => 'required',
            'total_money' => 'required',
            'amount_paid' => 'required'
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
            'maintenance_id.required'     => __('Hãy nhập mã phiếu bảo trì.'),
            'maintenance_code.required'     => __('Hãy nhập mã phiếu bảo trì.'),
            'customer_id.required'     => __('Hãy chọn khách hàng.'),
            'total_money.required'     => __('Hãy nhập tổng tiền cần trả.'),
            'amount_paid.required'     => __('Hãy nhập tổng tiền khách trả.'),
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
            'maintenance_id' => 'strip_tags|trim',
            'maintenance_code' => 'strip_tags|trim',
            'customer_id' => 'strip_tags|trim',
            'total_money' => 'strip_tags|trim',
            'amount_paid' => 'strip_tags|trim',
            'amount_return' => 'strip_tags|trim',
            'note' => 'strip_tags|trim',
        ];
    }
}