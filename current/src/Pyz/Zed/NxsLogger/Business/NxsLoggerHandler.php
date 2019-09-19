<?php

namespace Pyz\Zed\NxsLogger\Business;

use Pyz\Zed\NxsLogger\Model\NxsLoggerInterface;
use Pyz\Zed\NxsLogger\NxsLoggerConfig;
use function in_array;

class NxsLoggerHandler
{
    /**
     * @var \Shared\Zed\NxsLogger\NxsLoggerConfig
     */
    public $nxsLoggerConfig;

    /**
     * @var \Shared\Zed\NxsLogger\Model\NxsLoggerInterface
     */
    public $logger;

    /**
     * @param \Shared\Zed\NxsLogger\NxsLoggerConfig $nxsLoggerConfig
     * @param \Shared\Zed\NxsLogger\Model\NxsLoggerInterface $logger
     */
    public function __construct(NxsLoggerConfig $nxsLoggerConfig, NxsLoggerInterface $logger)
    {
        $this->nxsLoggerConfig = $nxsLoggerConfig;
        $this->logger = $logger;
    }

    /**
     * @param string $message
     * @param string $ident
     *
     * @return bool
     */
    public function log(string $message, string $ident = 'DEFAULT_IDENTIFICATION'): bool
    {
        $logged = false;
        $enabled = $this->nxsLoggerConfig->getNxsLoggerEnabled();
        $arr = $this->nxsLoggerConfig->getNxsLoggerIdents();
        $inArray = in_array($ident, $arr, true);
        if ($enabled
            && $inArray) {
            $logged = true;
            $this->logger->log($message, $ident);
        }
        return $logged;
    }
}
