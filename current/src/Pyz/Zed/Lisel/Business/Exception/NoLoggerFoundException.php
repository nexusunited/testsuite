<?php

namespace Pyz\Zed\Lisel\Business\Exception;

use Exception;

class NoLoggerFoundException extends ExceptionAbstract
{
    /**
     * @param string $message
     * @param int $code
     * @param \Exception|null $previous
     */
    public function __construct(
        $message = 'Request and Response logging is activated, but not implemented.',
        $code = 1,
        ?Exception $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }
}
