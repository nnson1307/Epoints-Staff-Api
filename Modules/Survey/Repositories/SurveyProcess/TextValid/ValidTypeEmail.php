<?php
namespace Modules\Survey\Repositories\SurveyProcess\TextValid;

use Illuminate\Support\Facades\Validator;

/**
 * Class ValidTypeEmail
 * @package Modules\Survey\Repositories\SurveyProcess\TextValid
 * @author DaiDP
 * @since Feb, 2022
 */
class ValidTypeEmail extends TextValidAbstract
{
    /**
     * Xử lý validate input
     * @param $value
     * @param array $option
     * @return boolean
     */
    public function isValid($value, array $option = [])
    {
        $validator = Validator::make(['val' => $value], [
            'val' => 'nullable|regex:/^[a-zA-Z0-9_.+-]+@[a-zA-Z0-9-]+\.[a-zA-Z0-9-.]*/'
        ]);

        return !$validator->fails();
    }
}