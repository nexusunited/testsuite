<?php

namespace Pyz\Zed\Lisel\Business\Exception;

use Exception;

class UnknownException extends ExceptionAbstract
{
    /**
     * @param string $message
     * @param int $code
     * @param \Exception|null $previous
     */
    public function __construct(
        $message = 'Server Error - Please contact us with details to your request.',
        $code = 500,
        ?Exception $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }
}
