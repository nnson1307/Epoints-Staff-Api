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

class AddTagRequest extends BaseFormRequest
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
            "name" => "required|unique:cpo_tag,name"
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

            'name.required'     => __('Tên thẻ không được để trống.'),
            'name.unique'     => __('Tên thẻ đã tồn tại.'),

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