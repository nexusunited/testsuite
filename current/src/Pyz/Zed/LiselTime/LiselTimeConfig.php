<?php

namespace Pyz\Zed\LiselTime;

use NxsSpryker\Zed\Configuration\PersistenceConfigurationTrait;
use Pyz\Shared\Configurations\ConfigurationsConstants;
use Pyz\Shared\DateFormat\DateFormatConstants;
use Pyz\Shared\LiselTime\LiselTimeConstants;
use Pyz\Zed\MyDeliveries\MyDeliveriesConfig;
use Spryker\Zed\Kernel\AbstractBundleConfig;

class LiselTimeConfig extends AbstractBundleConfig implements LiselTimeConfigInterface
{
    use PersistenceConfigurationTrait;

    /**
     * @return int
     */
    public function getEtaUpdateShortBeforeMin(): int
    {
        return $this->get(LiselTimeConstants::ETA_UPDATE_SHORT_BEFORE_MIN, 0);
    }

    /**
     * @return string
     */
    public function getEtaPlus(): string
    {
        return $this->get(MyDeliveriesConfig::ETA_PLUS, '+ 30 minutes');
    }

    /**
     * @return string
     */
    public function getEtaMinus(): string
    {
        return $this->get(MyDeliveriesConfig::ETA_MINUS, '- 30 minutes');
    }

    /**
     * @return string
     */
    public function getIncommingDateFormat(): string
    {
        return $this->get(DateFormatConstants::INCOMMING_TIMEZONE, 'Europe/Berlin');
    }

    /**
     * @return array|mixed|string
     */
    public function getDeliveryTimeFrameDelayDeviation()
    {
        return $this->getFromPersistence(ConfigurationsConstants::LISEL_DELIVERY_TIME_DELAY_DEVIATION);
    }

    /**
     * @return int
     */
    public function getUpdateEtaDelayTime(): int
    {
        return $this->getFromPersistence(ConfigurationsConstants::LISEL_UPDATE_DELAY_TIME_NAME);
    }
}
