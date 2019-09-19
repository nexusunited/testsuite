<?php

namespace Pyz\Zed\DateFormat\Business;

use Pyz\Zed\DateFormat\Model\DateTransformator;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

/**
 * @method \Pyz\Zed\DateFormat\DateFormatConfig getConfig()
 */
class DateFormatBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Pyz\Zed\DateFormat\Model\DateTransformator
     */
    public function createDateTransformator()
    {
        return new DateTransformator(
            $this->getConfig()->getIncommingDateFormat(),
            $this->getConfig()->getOutgoingDateFormat(),
            $this->getConfig()->getOutputFormat()
        );
    }
}
