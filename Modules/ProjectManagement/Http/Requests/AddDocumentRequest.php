<?php


namespace Modules\ProjectManagement\Http\Requests;

use MyCore\Http\Request\BaseFormRequest;

class AddDocumentRequest extends BaseFormRequest
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
            'file_name' => 'required',
//            'type' => 'required',
            'path' => 'required'
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
            'manage_project_id.required' => __('Nhập id dự án.'),
            'manage_project_id.integer' => __('Id dự án không đúng kiểu dữ liệu.'),
            'file_name.required' => __('Vui lòng nhập tên tài liệu.'),
//            'type.required'     => __('Vui lòng nhập loại tài liệu.'),
            'path.required' => __('Vui lòng chọn tài liệu.'),
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