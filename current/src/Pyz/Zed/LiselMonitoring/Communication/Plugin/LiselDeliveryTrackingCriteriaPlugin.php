<?php

namespace Pyz\Zed\LiselMonitoring\Communication\Plugin;

use NxsSpryker\Zed\Monitoring\Communication\Plugin\MonitoringCriteriaPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use function ceil;
use function date;
use function floor;
use function in_array;
use function strtotime;
use function time;

/**
 * @method \Shared\Zed\LiselMonitoring\Communication\LiselMonitoringCommunicationFactory getFactory()
 * @method \Shared\Zed\LiselMonitoring\LiselMonitoringConfig getConfig()
 */
class LiselDeliveryTrackingCriteriaPlugin extends AbstractPlugin implements MonitoringCriteriaPluginInterface
{
    public const CRITERIA_NAME = 'Lisel delivery tracking is being received';
    public const DATE_FORMAT = 'Y-m-d';

    /**
     * @return string
     */
    public function getCriteriaName(): string
    {
        return self::CRITERIA_NAME;
    }

    /**
     * @return bool
     */
    public function isCriteriaMet(): bool
    {
        $liselRequestFacade = $this->getFactory()->getLiselRequestFacade();

        if ($this->isMonitoringEnabled() === false) {
            $liselRequestFacade->setLastLiselRequestTimeStamp();

            return true;
        }

        if ($this->isExcludedDay(time())) {
            return true;
        }

        $lastRequestTimestamp = $liselRequestFacade->getLastLiselRequestTimeStamp();

        if ($lastRequestTimestamp === null) {
            $liselRequestFacade->setLastLiselRequestTimeStamp();

            return true;
        }

        $maxRequestInterval = $this->getConfig()->getMaxLiselRequestInterval();
        $currentInterval = $this->calculateIntervalInHours($lastRequestTimestamp);

        return $currentInterval <= $maxRequestInterval;
    }

    /**
     * @return bool
     */
    private function isMonitoringEnabled(): bool
    {
        return (bool)$this->getConfig()->getMonitoringIsActive();
    }

    /**
     * @param int $timestamp
     *
     * @return bool
     */
    private function isExcludedDay(int $timestamp): bool
    {
        $monitoringWeekdays = $this->getConfig()->getMonitoringWeekdays();
        $currentWeekday = date('w', $timestamp);
        if (!in_array($currentWeekday, $monitoringWeekdays)) {
            return true;
        }

        $excludedDays = $this->getConfig()->getExcludedDays();
        $today = date(self::DATE_FORMAT, $timestamp);
        /** @var \DateTime $excludedDay */
        foreach ($excludedDays as $excludedDay) {
            if ($excludedDay->format(self::DATE_FORMAT) === $today) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param int $lastRequestTimestamp
     *
     * @return int
     */
    private function calculateIntervalInHours(int $lastRequestTimestamp): int
    {
        $intervalInHours = ceil((time() - $lastRequestTimestamp) / 3600);
        $daysFromLastTimestamp = floor($intervalInHours / 24);

        $totalExcludedDays = 0;
        while ($daysFromLastTimestamp > 0) {
            if ($this->isExcludedDay(strtotime("-$daysFromLastTimestamp days"))) {
                $totalExcludedDays++;
            }
            $daysFromLastTimestamp--;
        }
        $excludedHours = $totalExcludedDays * 24;

        return $intervalInHours - $excludedHours;
    }
}
