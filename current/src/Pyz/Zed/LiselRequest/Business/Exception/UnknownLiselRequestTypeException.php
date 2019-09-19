<?php

namespace Pyz\Zed\LiselRequest\Business\Exception;

use Exception;
use Pyz\Zed\Lisel\Business\Exception\ExceptionAbstract;

class UnknownLiselRequestTypeException extends ExceptionAbstract
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
        parent::__construct("LiselRequestType not found. ($details)", $code, $previous);
    }
}
