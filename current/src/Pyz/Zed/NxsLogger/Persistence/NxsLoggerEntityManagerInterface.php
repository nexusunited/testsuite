<?php

namespace Pyz\Zed\NxsLogger\Persistence;

interface NxsLoggerEntityManagerInterface
{
    /**
     * @param string $range
     *
     * @return void
     */
    public function removeOldLoggingEntries(string $range): void;
}
