<?php

namespace Pyz\Zed\LiselEvent\Business\Exception;

use Exception;
use Pyz\Zed\Lisel\Business\Exception\ExceptionAbstract;

class UnknownLiselEventTypeException extends ExceptionAbstract
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
        parent::__construct("LiselEventType not found. ($details)", $code, $previous);
    }
}
