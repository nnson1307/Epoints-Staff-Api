<?php

namespace Modules\ManageWork\Http\Requests\Work;

use MyCore\Http\Request\BaseFormRequest;

class JobOverViewRequest extends BaseFormRequest
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
            'from_date'    => 'nullable',
            'to_date'    => 'nullable',
            'branch_id'    => 'nullable',
            'department_id'    => 'nullable',
            'manage_project_id'    => 'nullable',

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
            'from_date' => 'strip_tags|trim',
            'to_date' => 'strip_tags|trim',
            'branch_id' => 'strip_tags|trim',
            'department_id' => 'strip_tags|trim',
            'manage_project_id' => 'strip_tags|trim',
        ];
    }
}