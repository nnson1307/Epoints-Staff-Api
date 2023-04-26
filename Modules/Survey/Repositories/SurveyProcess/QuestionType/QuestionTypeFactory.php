<?php
namespace Modules\Survey\Repositories\SurveyProcess\QuestionType;

use Modules\Survey\Repositories\SurveyProcess\SurveyProcessException;

/**
 * Class QuestionTypeFactory
 * @package Modules\Survey\Repositories\SurveyProcess\QuestionType
 * @author DaiDP
 * @since Feb, 2022
 */
class QuestionTypeFactory
{
    const SINGLE_CHOICE = 'single_choice';
    const MULTI_CHOICE = 'multi_choice';
    const TEXT_ENTRY = 'text';
    const PAGE_TEXT = 'page_text';
    const PAGE_PICTURE = 'page_picture';

    /**
     * Khởi tạo xử lý loại câu hỏi
     * @param $type
     * @return QuestionTypeAbstract
     * @throws SurveyProcessException
     */
    public static function getInstance($type)
    {
        switch ($type)
        {
            case self::SINGLE_CHOICE:
                return app()->get(TypeSingleChoice::class);

            case self::MULTI_CHOICE:
                return app()->get(TypeMultiChoice::class);

            case self::TEXT_ENTRY:
                return app()->get(TypeText::class);

            case self::PAGE_TEXT:
            case self::PAGE_PICTURE:
                return app()->get(TypePageText::class);

            default:
                throw new SurveyProcessException(SurveyProcessException::QUESTION_TYPE_NOT_SUPPORT);
        }
    }
}