<?php
namespace Modules\Survey\Http\Requests\ListData;

use MyCore\Http\Request\BaseFormRequest;

/**
 * Class HistoryPreviewRequest
 * RET-1765
 * @package Modules\Survey\Http\Requests\ListData
 * @author DaiDP
 * @since Mar, 2022
 */
class HistoryPreviewRequest extends BaseFormRequest
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
            'survey_answer_id' => 'required|int',
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
            'survey_answer_id.required' => __('ID Lịch sử là thông tin bắt buộc'),
            'survey_answer_id.integer' => __('ID Lịch sử không đúng định dạng'),
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
            'survey_answer_id' => 'strip_tags|trim',
            'question_no' => 'strip_tags|trim'
        ];
    }
}