<?php

namespace Pyz\Zed\LiselAnalyser\Communication\Events;

use DateTime;
use Generated\Shared\Transfer\LiselEventTransfer;
use Orm\Zed\Customer\Persistence\SpyCustomer;
use Pyz\Zed\LiselEvent\Plugin\AbstractLiselEvent;
use Pyz\Zed\LiselEvent\Plugin\LiselEventInterface;
use Pyz\Zed\MyDeliveries\Business\MyDeliveriesFacadeInterface;
use Spryker\Shared\Kernel\Transfer\TransferInterface;

/**
 * @method \Shared\Zed\LiselAnalyser\Business\LiselAnalyserFacade getFacade()
 * @method \Shared\Zed\LiselAnalyser\LiselAnalyserConfig getConfig()
 */
abstract class AbstractLiselAnalyserEvent extends AbstractLiselEvent implements LiselEventInterface
{
    /**
     * @return \Generated\Shared\Transfer\LiselEventTransfer
     */
    public function getLiselEventTransfer(): LiselEventTransfer
    {
        $transfer = new LiselEventTransfer();
        $transfer->setStoppId($this->getTransferData()->getStoppId());
        $transfer->setDateTime(new DateTime($this->getTransferData()->getDateTime()));
        $transfer->setEventName($this->getType());
        return $transfer;
    }

    /**
     * @return \Generated\Shared\Transfer\LiselEventTransfer
     */
    public function getTransferData(): TransferInterface
    {
        return parent::getTransferData();
    }

    /**
     * @return bool
     */
    public function isResponsible(): bool
    {
        return true;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return 'DEFAULT_EVENT_TYPE';
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
            new DateTime($this->getTransferData()->getDateTime()),
            $this->getConfig()->getEtaMinus(),
            $this->getConfig()->getEtaPlus()
        );
        $minsTimeSpanToNow = $this->getDateTimeDiffToNow($startEndDateTime['start']->format('Y-m-d H:i:s'));
        if ($minsTimeSpanToNow > 0 &&
            $minsTimeSpanToNow < $this->getConfig()->getEtaUpdateShortBeforeMin()) {
            $startInNextMinutes = true;
        }
        return $startInNextMinutes;
    }

    /**
     * @param string $date1
     *
     * @return float|int
     */
    private function getDateTimeDiffToNow(string $date1)
    {
        $etaDate = new DateTime($date1);
        $currentDate = new DateTime();
        return ($etaDate->getTimestamp() -
                $currentDate->getTimestamp()) / 60;
    }

    /**
     * @param \Orm\Zed\Customer\Persistence\SpyCustomer $customers
     *
     * @return bool
     */
    public function canBeNotified(SpyCustomer $customers): bool
    {
        return true;
    }
}
