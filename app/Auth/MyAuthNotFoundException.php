<?php
namespace App\Auth;

use Throwable;

/**
 * Class MyAuthNotFoundException
 * @package App\Auth
 * @author DaiDP
 * @since Sep, 2019
 */
class MyAuthNotFoundException extends \Exception
{
    public function __construct(string $message = "", int $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}