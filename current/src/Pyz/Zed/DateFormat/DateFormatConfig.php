<?php

namespace Pyz\Zed\DateFormat;

use Pyz\Shared\DateFormat\DateFormatConstants;
use Spryker\Zed\Kernel\AbstractBundleConfig;

class DateFormatConfig extends AbstractBundleConfig
{
    /**
     * @return string
     */
    public function getIncommingDateFormat(): string
    {
        return $this->get(DateFormatConstants::INCOMMING_TIMEZONE, 'Europe/Berlin');
    }

    /**
     * @return string
     */
    public function getOutgoingDateFormat(): string
    {
        return $this->get(DateFormatConstants::OUTGOING_TIMEZONE, 'UTC');
    }

    /**
     * @return string
     */
    public function getOutputFormat(): string
    {
        return $this->get(DateFormatConstants::OUTPUT_FORMAT, 'Y-m-d H:i:s');
    }
}
