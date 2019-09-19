<?php

namespace PyzTest\_support;

use DateInterval;
use Datetime;
use DateTimeZone;
use Pyz\Zed\LiselAnalyser\LiselAnalyserConfig;
use Pyz\Zed\MyDeliveries\MyDeliveriesConfig;

class DateHelper
{
    public const DELIVERY_START_FORMAT = 'H.i';

    /**
     * @var string
     */
    private $timeZone;

    /**
     * @var string
     */
    private $etaPlus;

    /**
     * @var string
     */
    private $etaMinus;

    public function __construct()
    {
        $this->timeZone = (new MyDeliveriesConfig())->getIncommingDateFormat();
        $this->etaPlus = (new LiselAnalyserConfig())->getEtaPlus();
        $this->etaMinus = (new LiselAnalyserConfig())->getEtaMinus();
    }

    /**
     * @param \Datetime $date
     *
     * @return \Datetime
     */
    public function roundupMinutesFromDatetime(Datetime $date): Datetime
    {
        $string = sprintf(
            "%d minutes %d seconds",
            $date->format("i") % 5,
            $date->format("s")
        );
        $date->sub(DateInterval::createFromDateString($string));
        $date->setTimezone(new DateTimeZone($this->timeZone));
        return $date;
    }

    /**
     * @param \Datetime $date
     *
     * @return string
     */
    public function getEtaStart(Datetime $date): string
    {
        $date = $this->roundupMinutesFromDatetime($date);
        return $date->modify($this->etaMinus)->format(self::DELIVERY_START_FORMAT);
    }

    /**
     * @param \DateTime $date
     *
     * @return string
     */
    public function getEtaStop(Datetime $date): string
    {
        $date = $this->roundupMinutesFromDatetime($date);
        return $date->modify($this->etaPlus)->format(self::DELIVERY_START_FORMAT);
    }
}
