<?php
namespace Modules\Survey\Http\Requests\SurveyProcess;

use MyCore\Http\Request\BaseFormRequest;

/**
 * Class StartRequest
 * @package Modules\Survey\Http\Requests\SurveyProcess
 * @author DaiDP
 * @since Feb, 2022
 */
class StartRequest extends BaseFormRequest
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
//            'branch_id' => 'required',
            'survey_id' => 'required|int',
            'question_no' => 'int|nullable'
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
            'branch_id.required'  => __('Hãy chọn chi nhánh.'),
            'survey_id.required'  => __('ID khảo sát bắt buộc'),
            'survey_id.integer'  => __('ID khảo sát không đúng định dạng'),
            'question_no.integer'  => __('Thứ tự câu hỏi không đúng định dạng'),
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
            'branch_id' => 'strip_tags|trim',
            'survey_id' => 'strip_tags|trim',
            'question_no' => 'strip_tags|trim'
        ];
    }
}