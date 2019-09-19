<?php

namespace Pyz\Zed\LiselTime\Business\Exception;

use Exception;
use Pyz\Zed\Lisel\Business\Exception\ExceptionAbstract;

class TimeMessageNotFoundException extends ExceptionAbstract
{
    /**
     * @param string $details
     * @param int $code
     * @param \Exception|null $previous
     */
    public function __construct(
        $details = '',
        $code = 401,
        ?Exception $previous = null
    ) {
        parent::__construct("Timemessage with stoppId ($details) not found.", $code, $previous);
    }
}
