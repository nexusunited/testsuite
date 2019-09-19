<?php

namespace Pyz\Zed\LiselNotifier\Business\Exception;

use Exception;
use Pyz\Zed\Lisel\Business\Exception\ExceptionAbstract;

class CouldNotWriteLogException extends ExceptionAbstract
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
        parent::__construct("Could not write Scenario Log. ($details)", $code, $previous);
    }
}
