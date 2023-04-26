<?php
namespace Modules\Survey\Http\Requests\SurveyProcess;

use MyCore\Http\Request\BaseFormRequest;

/**
 * Class SubmitRequest
 * @package Modules\Survey\Http\Requests\SurveyProcess
 * @author DaiDP
 * @since Feb, 2022
 */
class SubmitRequest extends BaseFormRequest
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
            'survey_question_id' => 'required|int',
            'submit_answer' => 'array|nullable'
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
            'survey_question_id.required'  => __('ID câu hỏi bắt buộc'),
            'survey_question_id.integer'  => __('ID câu hỏi không đúng định dạng'),
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
            'survey_question_id' => 'strip_tags|trim'
        ];
    }
}