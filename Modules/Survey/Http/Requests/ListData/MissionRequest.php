<?php
namespace Modules\Survey\Http\Requests\ListData;

use MyCore\Http\Request\BaseFormRequest;

/**
 * Class MissionRequest
 * @package Modules\Survey\Http\Requests\ListData
 * @author DaiDP
 * @since Feb, 2022
 */
class MissionRequest extends BaseFormRequest
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
            'date_start' => 'nullable|date_format:Y-m-d',
            'date_end' => 'nullable|date_format:Y-m-d',
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
            'date_start.date_format'  => __('Ngày bắt đầu để lọc không đúng định dạng'),
            'date_end.date_format'  => __('Ngày kết thúc để lọc không đúng định dạng'),
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
            'date_start' => 'strip_tags|trim',
            'date_end' => 'strip_tags|trim'
        ];
    }
}