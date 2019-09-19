<?php

namespace Pyz\Zed\RestRequest\Business\Exception;

use Exception;
use Pyz\Zed\Lisel\Business\Exception\ExceptionAbstract;

class JsonException extends ExceptionAbstract
{
    /**
     * @param string $message
     * @param int $code
     * @param \Exception|null $previous
     */
    public function __construct(
        $message = 'Bad request. JSON string must be valid.',
        $code = 400,
        ?Exception $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }
}
