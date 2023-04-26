<?php

/**
 * @Author : VuND
 */

namespace Modules\Payment\Repositories;


use MyCore\Repository\RepositoryTitleExceptionAbstract;

class PaymentException extends RepositoryTitleExceptionAbstract
{
    const SYS_UNKNOWN_METHOD = 'sys_unknown_method';
}
