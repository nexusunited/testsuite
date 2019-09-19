<?php

namespace Pyz\Zed\NxsLogger\Business;

interface NxsLoggerFacadeInterface
{
    /**
     * @param string $message
     * @param string $ident
     *
     * @return \Generated\Shared\Transfer\NxsLoggerTransfer
     */
    public function log(string $message, string $ident = 'DEFAULT_IDENTIFICATION');

    /**
     * @return void
     */
    public function removeOldLoggingEntries(): void;
}
