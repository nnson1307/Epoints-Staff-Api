<?php
namespace Modules\User\Http\Validators;

use Illuminate\Contracts\Validation\Validator as ValidatorContract;
use Modules\User\Models\PhoneServiceTable;

/**
 * Class PhoneValidator
 * @package Modules\User\Http\Validators
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
        // lấy 3 số đầu của mobile
        $serNum = substr($value, 0, 3);

        // Check trong db đầu số có không
        $mPhoneService = app(PhoneServiceTable::class);
        $info = $mPhoneService->getServiceInfo($serNum);

        return $info ? true : false;
    }
}