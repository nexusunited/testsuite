<?php

namespace Pyz\Zed\NxsLogger\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

/**
 * @method \Shared\Zed\NxsLogger\NxsLoggerConfig getConfig()
 */
class NxsLoggerBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Shared\Zed\NxsLogger\Model\NxsLoggerInterface
     */
    public function createLogger()
    {
        return new NxsLoggerHandler($this->getConfig(), $this->getConfig()->getNxsLoggerClass());
    }

    /**
     * @return string
     */
    public function getNxsLoggerEntryMaxAge(): string
    {
        return $this->getConfig()->getNxsLoggerEntryMaxAge();
    }
}
