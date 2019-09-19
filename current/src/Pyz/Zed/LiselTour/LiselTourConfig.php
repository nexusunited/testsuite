<?php

namespace Pyz\Zed\LiselTour;

use Pyz\Zed\MyDeliveries\MyDeliveriesConfig;
use Spryker\Zed\Kernel\AbstractBundleConfig;

class LiselTourConfig extends AbstractBundleConfig implements LiselTourConfigInterface
{
    /**
     * @return string
     */
    public function getPtaPlus(): string
    {
        return $this->get(MyDeliveriesConfig::PTA_PLUS, '+ 1 hour');
    }

    /**
     * @return string
     */
    public function getPtaMinus(): string
    {
        return $this->get(MyDeliveriesConfig::PTA_MINUS, '- 1 hour');
    }
}
