<?php

namespace Pyz\Zed\NxsLogger;

use Pyz\Shared\NxsLogger\NxsLoggerConstants;
use Pyz\Zed\NxsLogger\Model\NxsDatabaseLogger;
use Pyz\Zed\NxsLogger\Model\NxsNoLogger;
use Spryker\Zed\Kernel\AbstractBundleConfig;

class NxsLoggerConfig extends AbstractBundleConfig
{
    public const NXS_LOGGER_IDENTS = 'NXS_LOGGER_IDENTS';
    public const NXS_LOGGER_CLASS = 'NXS_LOGGER_CLASS';
    public const NXS_LOGGER_ENABLED = 'NXS_LOGGER_ENABLED';
    public const DEFAULT_NXS_LOGGING_ENTRY_MAX_AGE = '-30 days';

    /**
     * @return array
     */
    public function getNxsLoggerIdents()
    {
        return $this->get(self::NXS_LOGGER_IDENTS, []);
    }

    /**
     * @return \Shared\Zed\NxsLogger\Model\NxsLoggerInterface
     */
    public function getNxsLoggerClass()
    {
        if ($this->getNxsLoggerEnabled()) {
            $logger = $this->get(self::NXS_LOGGER_CLASS, NxsDatabaseLogger::class);
            $logger = new $logger();
        } else {
            $logger = new NxsNoLogger();
        }
        return $logger;
    }

    /**
     * @return bool
     */
    public function getNxsLoggerEnabled(): bool
    {
        return $this->get(self::NXS_LOGGER_ENABLED, false);
    }

    /**
     * @return string
     */
    public function getNxsLoggerEntryMaxAge(): string
    {
        return $this->get(NxsLoggerConstants::NXS_LOGGING_ENTRY_MAX_AGE, self::DEFAULT_NXS_LOGGING_ENTRY_MAX_AGE);
    }
}
