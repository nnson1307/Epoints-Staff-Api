<?php
namespace Modules\Survey\Repositories\SurveyProcess\TextValid;

/**
 * Class ValidTypeNone
 * @package Modules\Survey\Repositories\SurveyProcess\TextValid
 * @author DaiDP
 * @since Feb, 2022
 */
class ValidTypeNone extends TextValidAbstract
{
    /**
     * Xử lý validate input
     * @param $value
     * @param array $option
     * @return boolean
     */
    public function isValid($value, array $option = [])
    {
        return true;
    }
}