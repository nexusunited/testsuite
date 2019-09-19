<?php

namespace Pyz\Zed\RestRequest\Business\Exception;

use Pyz\Zed\Lisel\Business\Exception\ExceptionAbstract;

class ValidationException extends ExceptionAbstract
{
    /**
     * @param string $details
     */
    public function __construct(string $details)
    {
        parent::__construct("JSON Validation Error ($details).", 600);
    }
}
