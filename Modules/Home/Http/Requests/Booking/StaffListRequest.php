<?php
/**
 * Created by PhpStorm   .
 * User: Mr Son
 * Date: 2020-02-17
 * Time: 3:15 PM
 * @author SonDepTrai
 */

namespace Modules\Home\Http\Requests\Booking;


use MyCore\Http\Request\BaseFormRequest;

class StaffListRequest extends BaseFormRequest
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
            'branch_id' => 'integer|required',
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
            'branch_id.integer'     => __('Kiểu dữ liệu không hợp lệ.'),
            'branch_id.required' => __('Hãy chọn chi nhánh.')
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