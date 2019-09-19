<?php

namespace Pyz\Zed\Lisel\Business\Exception;

use Exception;

class LiselActionException extends ExceptionAbstract
{
    /**
     * @param string $details
     * @param int $code
     * @param \Exception|null $previous
     */
    public function __construct(
        $details = '',
        $code = 500,
        ?Exception $previous = null
    ) {
        parent::__construct("Stop not found. ($details)", $code, $previous);
    }
}
