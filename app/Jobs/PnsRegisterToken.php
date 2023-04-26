<?php

namespace App\Jobs;

use App\Entities\RegisterPNSTokenMessage;

/**
 * Class PnsRegisterToken
 * @package App\Jobs
 * @author DaiDP
 * @since Jul, 2019
 */
class PnsRegisterToken extends BaseJob
{
    public $queue = 'noti';

    /**
     * @var RegisterPNSTokenMessage
     */
    protected $message;

    /**
     * PnsRegisterToken constructor.
     * @param RegisterPNSTokenMessage $message
     */
    public function __construct(RegisterPNSTokenMessage $message)
    {
        $this->message = $message;
    }
}
