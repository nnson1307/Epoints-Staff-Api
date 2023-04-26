<?php
namespace Modules\Survey\Repositories\SurveyProcess\TextValid;

use Illuminate\Support\Facades\Validator;

/**
 * Class ValidTypePhone
 * @package Modules\Survey\Repositories\SurveyProcess\TextValid
 * @author DaiDP
 * @since Feb, 2022
 */
class ValidTypePhone extends TextValidAbstract
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
            'val' => 'nullable|digits_between:10,11|phone'
        ]);

        return !$validator->fails();
    }
}