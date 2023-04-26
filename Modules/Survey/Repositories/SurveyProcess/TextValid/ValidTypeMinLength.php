<?php
namespace Modules\Survey\Repositories\SurveyProcess\TextValid;

use Illuminate\Support\Facades\Validator;

/**
 * Class ValidTypeMinLength
 * @package Modules\Survey\Repositories\SurveyProcess\TextValid
 * @author DaiDP
 * @since Feb, 2022
 */
class ValidTypeMinLength extends TextValidAbstract
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

        if (is_null($min)) {
            return false;
        }

        $validator = Validator::make(['val' => $value], [
            'val' => 'nullable|min:' . $min
        ]);

        return !$validator->fails();
    }
}