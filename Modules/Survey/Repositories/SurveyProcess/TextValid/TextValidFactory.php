<?php
namespace Modules\Survey\Repositories\SurveyProcess\TextValid;

/**
 * Class TextValidFactory
 * @package Modules\Survey\Repositories\SurveyProcess\TextValid
 * @author DaiDP
 * @since Feb, 2022
 */
class TextValidFactory
{
    const NONE = 'none';
    const MIN_LENGTH = 'min';
    const MAX_LENGTH = 'max';
    const BETWEEN_LENGTH = 'digits_between';
    const EMAIL = 'email';
    const PHONE = 'phone';
    const DATE = 'date';
    const DATE_FORMAT = 'date_format';
    const NUMERIC = 'numeric';

    /**
     * Khởi tạo xử lý loại câu hỏi
     * @param $type
     * @return TextValidAbstract
     */
    public static function getInstance($type)
    {
        switch ($type)
        {
            case self::MIN_LENGTH:
                return app()->get(ValidTypeMinLength::class);

            case self::MAX_LENGTH:
                return app()->get(ValidTypeMaxLength::class);

            case self::BETWEEN_LENGTH:
                return app()->get(ValidTypeBetweenLength::class);

            case self::EMAIL:
                return app()->get(ValidTypeEmail::class);

            case self::PHONE:
                return app()->get(ValidTypePhone::class);

            case self::DATE:
            case self::DATE_FORMAT:
                return app()->get(ValidTypeDate::class);

            case self::NUMERIC:
                return app()->get(ValidTypeNumeric::class);

            case self::NONE:
            default:
                return app()->get(ValidTypeNone::class);
        }
    }
}