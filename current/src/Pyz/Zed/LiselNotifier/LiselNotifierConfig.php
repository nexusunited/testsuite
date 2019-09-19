<?php

namespace Pyz\Zed\LiselNotifier;

use NxsSpryker\Zed\Configuration\PersistenceConfigurationTrait;
use Pyz\Shared\Configurations\ConfigurationsConstants;
use Pyz\Shared\LiselNotifier\LiselNotifierConstants;
use Spryker\Zed\Kernel\AbstractBundleConfig;

class LiselNotifierConfig extends AbstractBundleConfig
{
    use PersistenceConfigurationTrait;

    /**
     * @return array
     */
    public function getLimitedEvents(): array
    {
        return $this->get(LiselNotifierConstants::LIMITED_EVENTS);
    }

    /**
     * @return array
     */
    public function getAllowedEventsForNonRegisteredCustomers(): array
    {
        return $this->get(LiselNotifierConstants::NON_REGISTERED_CUSTOMERS_ALLOWED_EVENTS);
    }

    /**
     * @return string|null
     */
    public function getEventNotificationLimit(): ?string
    {
        return $this->getFromPersistence(ConfigurationsConstants::LISEL_EVENT_NOTIFICATION_LIMIT_NAME);
    }
}
