<?php

namespace Modules\ManageWork\Http\Requests\Remind;

use MyCore\Http\Request\BaseFormRequest;
use Illuminate\Validation\Rule;

/**
 * Class LoginRequest
 * @package Modules\User\Http\Requests\Authen
 * @author DaiDP
 * @since Aug, 2019
 */
class RemindDetailRequest extends BaseFormRequest
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
            'manage_remind_id'    => 'required',
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
            'manage_remind_id.required' => __('Vui lòng truyền id của nhắc nhở'),
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
            'list_staff' => 'strip_tags|trim',
            'date_remind' => 'strip_tags|trim',
            'time' => 'strip_tags|trim',
            'time_type' => 'strip_tags|trim',
            'manage_work_id' => 'strip_tags|trim'
        ];
    }
}