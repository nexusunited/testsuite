<?php

namespace Pyz\Zed\LiselTime\Dependency\Plugin\Events;

use DateTime;
use Generated\Shared\Transfer\LiselEventTransfer;
use Orm\Zed\Customer\Persistence\SpyCustomer;
use Pyz\Zed\LiselEvent\Plugin\AbstractLiselEvent;
use Pyz\Zed\LiselEvent\Plugin\LiselEventInterface;
use Pyz\Zed\MyDeliveries\Business\MyDeliveriesFacadeInterface;

/**
 * @method \Shared\Zed\LiselTime\Dependency\LiselTimeDependencyFactory getFactory()
 * @method \Shared\Zed\LiselTime\Business\LiselTimeFacade getFacade()
 * @method \Shared\Zed\LiselTime\LiselTimeConfig getConfig()
 */
abstract class AbstractLiselTimeEvent extends AbstractLiselEvent implements LiselEventInterface
{
    /**
     * @return \Generated\Shared\Transfer\LiselEventTransfer
     */
    public function getLiselEventTransfer(): LiselEventTransfer
    {
        $transfer = new LiselEventTransfer();
        $transfer->setStoppId($this->getTransferData()->getStoppId());
        $transfer->setDateTime(new DateTime($this->getTransferData()->getEta()));
        $transfer->setEventName($this->getType());

        return $transfer;
    }

    /**
     * @return \Generated\Shared\Transfer\LiselEventTransfer
     */
    public function getTransferData()
    {
        /** @var \Generated\Shared\Transfer\LiselEventTransfer $transferData */
        $transferData = parent::getTransferData();

        return $transferData;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return 'DEFAULT_EVENT_TYPE';
    }

    /**
     * @return bool
     */
    public function isResponsible(): bool
    {
        return true;
    }

    /**
     * @param \Orm\Zed\Customer\Persistence\SpyCustomer $customer
     *
     * @return bool
     */
    public function canBeNotified(SpyCustomer $customer): bool
    {
        return true;
    }

    /**
     * @param \Shared\Zed\MyDeliveries\Business\MyDeliveriesFacadeInterface $deliveriesFacade
     *
     * @return bool
     */
    protected function etaPeriodIsInNextMinutes(MyDeliveriesFacadeInterface $deliveriesFacade): bool
    {
        $startInNextMinutes = false;

        $startEndDateTime = $deliveriesFacade->getTimeSpan(
            new DateTime($this->getTransferData()->getEta()),
            $this->getConfig()->getEtaMinus(),
            $this->getConfig()->getEtaPlus()
        );
        $minsTimeSpanToNow = $this->getDateTimeDiff($startEndDateTime['start']->format('Y-m-d H:i:s'), (new DateTime())->format('Y-m-d H:i:s'));

        if ($minsTimeSpanToNow > 0 &&
            $minsTimeSpanToNow < $this->getConfig()->getEtaUpdateShortBeforeMin()) {
            $startInNextMinutes = true;
        }

        return $startInNextMinutes;
    }

    /**
     * @param string $date1
     * @param string $date2
     *
     * @return float|int
     */
    private function getDateTimeDiff(string $date1, string $date2)
    {
        $etaDate = new DateTime($date1);
        $currentDate = new DateTime($date2);

        return ($etaDate->getTimestamp() - $currentDate->getTimestamp()) / 60;
    }

    /**
     * @param \DateTime $compareDatetime
     *
     * @return bool
     */
    protected function isDelayed(DateTime $compareDatetime): bool
    {
        $isDelayed = false;
        $diff = $this->getDateTimeDiff($this->getTransferData()->getEta(), $compareDatetime->format('Y-m-d H:i:s'));
        $delayTime = $this->getConfig()->getUpdateEtaDelayTime();

        if ($diff > 0 && $diff > $delayTime) {
            $isDelayed = true;
        }

        return $isDelayed;
    }
}
