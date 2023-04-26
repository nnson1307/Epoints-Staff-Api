<?php
/**
 * Created by PhpStorm   .
 * User: Mr To
 * Date: 2022-09-13
 * Time: 3:00 PM
 * @author doanhongto
 */
namespace Modules\ProjectManagement\Http\Requests;


use MyCore\Http\Request\BaseFormRequest;

class ProjectIdRequestNew extends BaseFormRequest
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