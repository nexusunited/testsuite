<?php

namespace Pyz\Zed\NxsLogger\Model;

use Generated\Shared\Transfer\NxsLoggerTransfer;
use Orm\Zed\NxsLogger\Persistence\NxsLogging;

class NxsDatabaseLogger implements NxsLoggerInterface
{
    /**
     * @param string $message
     * @param string $ident
     *
     * @return \Generated\Shared\Transfer\NxsLoggerTransfer
     */
    public function log(string $message, string $ident): NxsLoggerTransfer
    {
        $nxsLogging = new NxsLogging();
        $nxsLogging->setMessage($message);
        $nxsLogging->setIdent($ident);
        $nxsLogging->save();
        $nxsLoggingTransfer = new NxsLoggerTransfer();
        $nxsLoggingTransfer->fromArray($nxsLogging->toArray());
        return $nxsLoggingTransfer;
    }
}
