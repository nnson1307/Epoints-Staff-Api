<?php


namespace Modules\ProjectManagement\Http\Requests;

use MyCore\Http\Request\BaseFormRequest;
class AddIssueRequest extends BaseFormRequest
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
            'manage_project_id' => 'integer|required',
            'content' => 'required',
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
            'manage_project_id.required'     => __('Nhập id dự án.'),
            'manage_project_id.integer'     => __('Id dự án không đúng kiểu dữ liệu.'),
            'content.required'     => __('Vui lòng nhập nội dung vấn đề.'),
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