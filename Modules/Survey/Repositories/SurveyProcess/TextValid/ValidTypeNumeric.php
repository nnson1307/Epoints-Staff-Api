<?php
namespace Modules\Survey\Repositories\SurveyProcess\TextValid;

use Illuminate\Support\Facades\Validator;

/**
 * Class ValidTypeNumeric
 * @package Modules\Survey\Repositories\SurveyProcess\TextValid
 * @author DaiDP
 * @since Feb, 2022
 */
class ValidTypeNumeric extends TextValidAbstract
{
    /**
     * Xử lý validate input
     * @param $value
     * @param array $option
     * @return boolean
     */
    public function isValid($value, array $option = [])
    {
        $min = $option['min'] ?? null;
        $max = $option['max'] ?? null;

        if (is_null($min) || is_null($max)) {
            return false;
        }

        $validator = Validator::make(['val' => $value], [
            'val' => sprintf('nullable|numeric|min:%s|max:%s', $min, $max)
        ]);

        return !$validator->fails();
    }
}