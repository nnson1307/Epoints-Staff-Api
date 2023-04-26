<?php

namespace Modules\Ticket\Http\Requests\RequestForm;

use MyCore\Http\Request\BaseFormRequest;
use Illuminate\Validation\Rule;

/**
 * Class LoginRequest
 * @package Modules\User\Http\Requests\Authen
 * @author DaiDP
 * @since Aug, 2019
 */
class InfoRequestFormRequest extends BaseFormRequest
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
            'ticket_id'    => 'required',
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
            'ticket_id.required' => __('Id ticket là thông tin bắt buộc'),
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
            'ticket_id' => 'strip_tags|trim'
        ];
    }
}