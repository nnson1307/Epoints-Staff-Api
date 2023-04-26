<?php
namespace Modules\Survey\Http\Validators;

use Illuminate\Contracts\Validation\Validator as ValidatorContract;

/**
 * Class PhoneValidator
 * @package Modules\Survey\Http\Validators
 * @author DaiDP
 * @since Aug, 2019
 */
class PhoneValidator
{
    /**
     * Validate phone number
     *
     * @param $attribute Key name
     * @param $value
     * @param $parameters Validate Options. EX: cablink:abc,def
     * @param $validator
     * @return bool
     */
    public function validate($attribute, $value, $parameters, ValidatorContract $validator)
    {
        $serNum = substr($value, 0, 1);
        return $serNum === '0';
    }
}