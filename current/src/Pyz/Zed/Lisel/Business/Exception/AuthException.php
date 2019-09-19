<?php

namespace Pyz\Zed\Lisel\Business\Exception;

use Exception;

class AuthException extends ExceptionAbstract
{
    /**
     * @param string $message
     * @param int $code
     * @param \Exception|null $previous
     */
    public function __construct(
        $message = 'Authentication Error - Please check your API Key.',
        $code = 401,
        ?Exception $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }
}
