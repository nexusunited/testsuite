<?php

namespace Pyz\Zed\Lisel\Business\Exception;

use Exception;

abstract class ExceptionAbstract extends Exception
{
    /**
     * @var string
     */
    private $type = 'Error';

    /**
     * @param string $message [optional] The Exception message to throw.
     * @param int $code [optional] The Exception code.
     * @param \Exception|null $previous [optional] The previous exception used for the exception chaining. Since 5.3.0
     */
    public function __construct(
        $message = '',
        $code = 404,
        $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }

    /**
     * @return array
     */
    public function getOutput()
    {
        return ['message' => $this->getMessage(), 'code' => $this->getCode(), 'type' => $this->type];
    }
}
