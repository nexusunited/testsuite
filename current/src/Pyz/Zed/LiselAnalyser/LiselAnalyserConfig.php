<?php

namespace Pyz\Zed\LiselAnalyser;

use Pyz\Shared\LiselAnalyser\LiselAnalyserConstants;
use Pyz\Shared\LiselTime\LiselTimeConstants;
use Pyz\Zed\MyDeliveries\MyDeliveriesConfig;
use Spryker\Zed\Kernel\AbstractBundleConfig;

class LiselAnalyserConfig extends AbstractBundleConfig implements LiselAnalyserConfigInterface
{
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
    public function getMaxIdleTime(): string
    {
        return $this->get(LiselAnalyserConstants::MAX_IDLE_AGE, '-30 minutes');
    }
}
