<?php

namespace Pyz\Zed\LiselMonitoring;

use NxsSpryker\Zed\Configuration\PersistenceConfigurationTrait;
use Pyz\Shared\Configurations\ConfigurationsConstants;
use Spryker\Zed\Kernel\AbstractBundleConfig;

class LiselMonitoringConfig extends AbstractBundleConfig
{
    use PersistenceConfigurationTrait;

    /**
     * @return bool
     */
    public function getMonitoringIsActive(): bool
    {
        return $this->getFromPersistence(ConfigurationsConstants::MONITORING_IS_ACTIVE);
    }

    /**
     * @return array
     */
    public function getMonitoringExceptionEmailRecipients(): array
    {
        return $this->getFromPersistence(ConfigurationsConstants::MONITORING_EXCEPTION_EMAIL_RECIPIENTS);
    }

    /**
     * @return string
     */
    public function getMaxLiselRequestInterval(): string
    {
        return $this->getFromPersistence(ConfigurationsConstants::MONITORING_EVENTS_MAX_INTERVAL);
    }

    /**
     * @return array
     */
    public function getMonitoringWeekdays(): array
    {
        return $this->getFromPersistence(ConfigurationsConstants::MONITORING_WEEKDAYS);
    }

    /**
     * @return array
     */
    public function getExcludedDays(): array
    {
        return $this->getFromPersistence(ConfigurationsConstants::MONITORING_EXCLUDED_DAYS);
    }
}
