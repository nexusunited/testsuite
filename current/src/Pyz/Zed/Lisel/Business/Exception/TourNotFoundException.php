<?php

namespace Pyz\Zed\Lisel\Business\Exception;

use Exception;

class TourNotFoundException extends ExceptionAbstract
{
    /**
     * @param string $details
     * @param int $code
     * @param \Exception|null $previous
     */
    public function __construct(
        $details = '',
        $code = 402,
        ?Exception $previous = null
    ) {
        parent::__construct("Tour not found. ($details)", $code, $previous);
    }
}
