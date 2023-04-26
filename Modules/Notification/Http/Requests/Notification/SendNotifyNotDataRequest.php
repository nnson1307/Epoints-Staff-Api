<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 29/04/2022
 * Time: 14:23
 */

namespace Modules\Notification\Http\Requests\Notification;

use MyCore\Http\Request\BaseFormRequest;

class SendNotifyNotDataRequest extends BaseFormRequest
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
            'staff_id' => 'required',
            'title' => 'required',
            'message' => 'required'
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
            'staff_id.required'     => __('Hãy nhập mã nhân viên.'),
            'title.required' => __('Hãy nhập tiêu đề.'),
            'message.required' => __('Hãy nhập nội dung gửi.'),
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
            'staff_id'     => 'strip_tags|trim',
            'title'     => 'strip_tags|trim',
            'content'     => 'strip_tags|trim'
        ];
    }
}