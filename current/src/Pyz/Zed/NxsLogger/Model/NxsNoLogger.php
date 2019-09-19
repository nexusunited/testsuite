<?php

namespace Pyz\Zed\NxsLogger\Model;

use Generated\Shared\Transfer\NxsLoggerTransfer;

class NxsNoLogger implements NxsLoggerInterface
{
    /**
     * @param string $message
     * @param string $ident
     *
     * @return \Generated\Shared\Transfer\NxsLoggerTransfer
     */
    public function log(string $message, string $ident): NxsLoggerTransfer
    {
        return new NxsLoggerTransfer();
    }
}
