<?php
namespace Modules\Survey\Repositories\SurveyProcess\TextValid;

/**
 * Class TextValidAbstract
 * @package Modules\Survey\Repositories\SurveyProcess\TextValid
 * @author DaiDP
 * @since Feb, 2022
 */
abstract class TextValidAbstract
{
    /**
     * Xử lý validate input
     * @param $value
     * @param array $option
     * @return boolean
     */
    abstract public function isValid($value, array $option = []);
}