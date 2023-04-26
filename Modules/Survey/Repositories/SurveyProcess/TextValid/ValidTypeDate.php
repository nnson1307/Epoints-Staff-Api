<?php
namespace Modules\Survey\Repositories\SurveyProcess\TextValid;

use Illuminate\Support\Facades\Validator;

/**
 * Class ValidTypeDate
 * @package Modules\Survey\Repositories\SurveyProcess\TextValid
 * @author DaiDP
 * @since Feb, 2022
 */
class ValidTypeDate extends TextValidAbstract
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
            'val' => 'nullable|date_format:Y-m-d'
        ]);

        return !$validator->fails();
    }
}