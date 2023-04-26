<?php
namespace Modules\User\Http\Requests\Device;

use MyCore\Http\Request\BaseFormRequest;
use Illuminate\Validation\Rule;

class CheckVersionRequest extends BaseFormRequest
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
            'platform'     => [
                'required',
                Rule::in(['android', 'ios'])
            ],
            'version'      => 'required',
            'release_date' => 'required'
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
            'platform.required'     => __('Platform là thông tin bắt buộc'),
            'platform.in'           => __('Platform không đúng. Platform phải là android hoặc ios'),
            'version.required'      => __('Version là thông tin bắt buộc'),
            'release_date.required' => __('Release Date là thông tin bắt buộc')
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
            'platform'     => 'strip_tags|trim',
            'version'      => 'strip_tags|trim',
            'release_date' => 'strip_tags|trim'
        ];
    }
}