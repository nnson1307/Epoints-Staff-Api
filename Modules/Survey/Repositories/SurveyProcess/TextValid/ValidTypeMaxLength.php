<?php
namespace Modules\Survey\Repositories\SurveyProcess\TextValid;

use Illuminate\Support\Facades\Validator;

/**
 * Class ValidTypeMaxLength
 * @package Modules\Survey\Repositories\SurveyProcess\TextValid
 * @author DaiDP
 * @since Feb, 2022
 */
class ValidTypeMaxLength extends TextValidAbstract
{
    /**
     * Xử lý validate input
     * @param $value
     * @param array $option
     * @return boolean
     */
    public function isValid($value, array $option = [])
    {
        $max = $option['max'] ?? null;

        if (is_null($max)) {
            return false;
        }

        $validator = Validator::make(['val' => $value], [
            'val' => 'nullable|max:' . $max
        ]);

        return !$validator->fails();
    }
}