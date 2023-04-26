<?php
namespace App\Entities;

use MyCore\Entities\JobMessageEntity;

/**
 * Class RegisterPNSTokenMessage
 * @package App\Models
 * @author DaiDP
 * @since Aug, 2019
 */
class RegisterPNSTokenMessage extends JobMessageEntity
{
    /**
     * @var int
     */
    public $user_id;

    /**
     * @var string
     */
    public $platform;

    /**
     * @var string
     */
    public $device_token;

    /**
     * @var string
     */
    public $imei;


    /**
     * RegisterPNSTokenMessage constructor.
     * @param array $data
     */
    public function __construct(array $data)
    {
        parent::__construct($data);
    }
}