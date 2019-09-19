<?php

namespace Pyz\Zed\LiselTime\Dependency\Plugin\Events;

use DateInterval;
use DateTime;
use Generated\Shared\Transfer\DeliveryTimeFrameCustomerEntriesTransfer;
use Generated\Shared\Transfer\DeliveryTimeFrameEntryTransfer;
use Orm\Zed\Customer\Persistence\SpyCustomer;
use Pyz\Service\DateTimeFormatter\DateTimeFormatterServiceInterface;
use Pyz\Shared\LiselEvent\LiselEventConstants;
use Pyz\Zed\DeliveryTimeFrame\Business\DeliveryTimeFrameFacadeInterface;
use Pyz\Zed\LiselTime\Persistence\LiselTimeRepositoryInterface;
use function explode;

/**
 * @method \Shared\Zed\LiselTime\Business\LiselTimeBusinessFactory getFactory()
 * @method \Shared\Zed\LiselTime\Business\LiselTimeFacade getFacade()
 * @method \Shared\Zed\LiselTime\LiselTimeConfig getConfig()
 */
class LiselDeliveryTimeFrameDelayDeviationEvent extends AbstractLiselTimeEvent
{
    /**
     * @var \Shared\Zed\DeliveryTimeFrame\Business\DeliveryTimeFrameFacadeInterface
     */
    private $deliveryTimeFrameFacade;

    /**
     * @var \Shared\Zed\LiselTime\Persistence\LiselTimeRepositoryInterface
     */
    private $liselTimeRepository;

    /**
     * @var \Shared\Service\DateTimeFormatter\DateTimeFormatterServiceInterface
     */
    private $dateTimeFormatterService;

    /**
     * @param \Shared\Zed\DeliveryTimeFrame\Business\DeliveryTimeFrameFacadeInterface $deliveryTimeFrameFacade
     * @param \Shared\Zed\LiselTime\Persistence\LiselTimeRepositoryInterface $liselTimeRepository
     * @param \Shared\Service\DateTimeFormatter\DateTimeFormatterServiceInterface $dateTimeFormatterService
     */
    public function __construct(
        DeliveryTimeFrameFacadeInterface $deliveryTimeFrameFacade,
        LiselTimeRepositoryInterface $liselTimeRepository,
        DateTimeFormatterServiceInterface $dateTimeFormatterService
    ) {
        $this->deliveryTimeFrameFacade = $deliveryTimeFrameFacade;
        $this->liselTimeRepository = $liselTimeRepository;
        $this->dateTimeFormatterService = $dateTimeFormatterService;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return LiselEventConstants::ETA_DTF_DELAY;
    }

    /**
     * @return bool
     */
    public function isResponsible(): bool
    {
        if (!$this->hasPreviousNotifications()) {
            return false;
        }

        return parent::isResponsible();
    }

    /**
     * @param \Orm\Zed\Customer\Persistence\SpyCustomer $customer
     *
     * @return bool
     */
    public function canBeNotified(SpyCustomer $customer): bool
    {
        if (!$this->hasDeliveryTimeFrames($customer)) {
            return false;
        }

        return !$this->deliveryTimeIsInTimeFrameWindow($customer);
    }

    /**
     * @return bool
     */
    private function hasPreviousNotifications(): bool
    {
        $nxsTimeItem = $this->liselTimeRepository->getLiselTimeByStoppId($this->getTransferData()->getStoppId());

        return $nxsTimeItem && $nxsTimeItem->getEta();
    }

    /**
     * @param \Orm\Zed\Customer\Persistence\SpyCustomer $customer
     *
     * @return bool
     */
    private function hasDeliveryTimeFrames(SpyCustomer $customer): bool
    {
        $dtfCustomerEntries = $this->deliveryTimeFrameFacade->getDeliveryTimeFramesForCustomerNumber(
            (new DeliveryTimeFrameCustomerEntriesTransfer())->setCustomerNumber($customer->getCustomerNumber())
        );

        return $dtfCustomerEntries->getDtfEntries()->count() > 0;
    }

    /**
     * @param \Orm\Zed\Customer\Persistence\SpyCustomer $customer
     *
     * @return bool
     */
    private function deliveryTimeIsInTimeFrameWindow(SpyCustomer $customer): bool
    {
        $timeDiff = $this->getEventTimeDiffMod($customer->getCustomerNumber());
        if ($timeDiff === null) {
            return false;
        }

        $deliveryTimeFrameDelayDeviation = (int)$this->getConfig()->getDeliveryTimeFrameDelayDeviation();

        return $timeDiff <= $deliveryTimeFrameDelayDeviation;
    }

    /**
     * @param string $customerNumber
     *
     * @return int|null
     */
    private function getEventTimeDiffMod(string $customerNumber): ?int
    {
        $expectedDeliveryTime = $this->dateTimeFormatterService->addDateTimeZoneOffset($this->getExpectedDeliveryTime());
        $deliveryTimeFrames = $this->getDeliveryTimeFrames($customerNumber);
        $weekday = $expectedDeliveryTime->format('w');
        $minDiff = null;

        foreach ($deliveryTimeFrames->getDtfEntries() as $dtfEntry) {
            $timeDiff = $this->getDeliveryTimeFrameMinuteDifference($dtfEntry, $expectedDeliveryTime, $weekday);
            if ($timeDiff === null) {
                continue;
            }
            if ($minDiff === null || $timeDiff < $minDiff) {
                $minDiff = $timeDiff;
            }
        }

        return $minDiff;
    }

    /**
     * @param string $customerNumber
     *
     * @return \Generated\Shared\Transfer\DeliveryTimeFrameCustomerEntriesTransfer
     */
    private function getDeliveryTimeFrames(string $customerNumber): DeliveryTimeFrameCustomerEntriesTransfer
    {
        $deliveryTimeFrames = new DeliveryTimeFrameCustomerEntriesTransfer();
        $deliveryTimeFrames->setCustomerNumber($customerNumber);

        return $this->deliveryTimeFrameFacade->getDeliveryTimeFramesForCustomerNumber($deliveryTimeFrames);
    }

    /**
     * @return \DateTime
     */
    private function getExpectedDeliveryTime(): DateTime
    {
        /** @var \Generated\Shared\Transfer\LiselTimeTransfer $expectedDeliveryTime */
        $expectedDeliveryTime = $this->getTransferData();
        $expectedDeliveryTime = $expectedDeliveryTime->getEta();

        if (!$expectedDeliveryTime instanceof DateTime) {
            $expectedDeliveryTime = new DateTime($expectedDeliveryTime);
        }

        return $expectedDeliveryTime;
    }

    /**
     * @param \Generated\Shared\Transfer\DeliveryTimeFrameEntryTransfer $deliveryTimeFrameEntryTransfer
     * @param \DateTime $expectedDeliveryTime
     * @param int $weekday
     *
     * @return int|null
     */
    private function getDeliveryTimeFrameMinuteDifference(
        DeliveryTimeFrameEntryTransfer $deliveryTimeFrameEntryTransfer,
        DateTime $expectedDeliveryTime,
        int $weekday
    ): ?int {
        switch ($weekday) {
            case 1:
                return $this->calculateTimeDifferenceInMinutes(
                    $deliveryTimeFrameEntryTransfer->getMonday(),
                    $expectedDeliveryTime,
                    $deliveryTimeFrameEntryTransfer->getMondayFrom(),
                    $deliveryTimeFrameEntryTransfer->getMondayTo()
                );
            case 2:
                return $this->calculateTimeDifferenceInMinutes(
                    $deliveryTimeFrameEntryTransfer->getTuesday(),
                    $expectedDeliveryTime,
                    $deliveryTimeFrameEntryTransfer->getTuesdayFrom(),
                    $deliveryTimeFrameEntryTransfer->getTuesdayTo()
                );
            case 3:
                return $this->calculateTimeDifferenceInMinutes(
                    $deliveryTimeFrameEntryTransfer->getWednesday(),
                    $expectedDeliveryTime,
                    $deliveryTimeFrameEntryTransfer->getWednesdayFrom(),
                    $deliveryTimeFrameEntryTransfer->getWednesdayTo()
                );
            case 4:
                return $this->calculateTimeDifferenceInMinutes(
                    $deliveryTimeFrameEntryTransfer->getThursday(),
                    $expectedDeliveryTime,
                    $deliveryTimeFrameEntryTransfer->getThursdayFrom(),
                    $deliveryTimeFrameEntryTransfer->getThursdayTo()
                );
            case 5:
                return $this->calculateTimeDifferenceInMinutes(
                    $deliveryTimeFrameEntryTransfer->getFriday(),
                    $expectedDeliveryTime,
                    $deliveryTimeFrameEntryTransfer->getFridayFrom(),
                    $deliveryTimeFrameEntryTransfer->getFridayTo()
                );
            case 6:
                return $this->calculateTimeDifferenceInMinutes(
                    $deliveryTimeFrameEntryTransfer->getSaturday(),
                    $expectedDeliveryTime,
                    $deliveryTimeFrameEntryTransfer->getSaturdayFrom(),
                    $deliveryTimeFrameEntryTransfer->getSaturdayTo()
                );
            default:
                return null;
        }
    }

    /**
     * @param bool $scheduledWeekday
     * @param \DateTime $expectedDeliveryTime
     * @param string|null $timeFrom
     * @param string|null $timeTo
     *
     * @return int|null
     */
    private function calculateTimeDifferenceInMinutes(
        bool $scheduledWeekday,
        DateTime $expectedDeliveryTime,
        ?string $timeFrom,
        ?string $timeTo
    ): ?int {
        if (!$scheduledWeekday) {
            return null;
        }
        $diff = 0;
        $timeWindowFrom = $this->formatTimeWindowTimeToDateTime($expectedDeliveryTime, $timeFrom);
        $timeWindowTo = $this->formatTimeWindowTimeToDateTime($expectedDeliveryTime, $timeTo);

        if ($expectedDeliveryTime >= $timeWindowFrom && $expectedDeliveryTime <= $timeWindowTo) {
            return $diff;
        }

        if ($timeWindowFrom > $expectedDeliveryTime) {
            $diff = $this->getTimeDiffInMinutes($expectedDeliveryTime, $timeWindowFrom);
        }

        if ($timeWindowTo < $expectedDeliveryTime) {
            $diff = $this->getTimeDiffInMinutes($expectedDeliveryTime, $timeWindowTo);
        }

        return $diff;
    }

    /**
     * @param \DateTime $expectedDeliveryTime
     * @param string $timeWindowTime
     *
     * @return \DateTime
     */
    private function formatTimeWindowTimeToDateTime(DateTime $expectedDeliveryTime, string $timeWindowTime): DateTime
    {
        $timeWindow = clone $expectedDeliveryTime;
        $hourMinuteSecond = explode(':', $timeWindowTime);
        $timeWindow->setTime($hourMinuteSecond[0], $hourMinuteSecond[1], $hourMinuteSecond[2]);

        return $timeWindow;
    }

    /**
     * @param \DateTime $expectedDeliveryTime
     * @param \DateTime $timeWindow
     *
     * @return int|null
     */
    private function getTimeDiffInMinutes(DateTime $expectedDeliveryTime, DateTime $timeWindow): ?int
    {
        $interval = $expectedDeliveryTime->diff($timeWindow);
        if ($interval === false) {
            return null;
        }

        return $this->getIntervalInMinutes($interval);
    }

    /**
     * @param \DateInterval $interval
     *
     * @return int
     */
    private function getIntervalInMinutes(DateInterval $interval): int
    {
        return $interval->h * 60 + $interval->i;
    }
}
