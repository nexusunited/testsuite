<?php

namespace Pyz\Zed\LiselTime\Dependency\Plugin\Events;

use DateTime;
use Exception;
use Generated\Shared\Transfer\CommunicationCustomerTransfer;
use Orm\Zed\Customer\Persistence\SpyCustomer;
use Orm\Zed\LiselTime\Persistence\NxsTimeQuery;
use Orm\Zed\LiselTour\Persistence\NxsStopQuery;
use Pyz\Shared\LiselEvent\LiselEventConstants;
use Pyz\Zed\CommunicationTracker\Business\CommunicationTrackerFacadeInterface;
use Pyz\Zed\LiselStopStatus\Business\LiselStopStatusFacadeInterface;
use Pyz\Zed\MyDeliveries\Business\MyDeliveriesFacadeInterface;

/**
 * @method \Shared\Zed\LiselTime\Business\LiselTimeBusinessFactory getFactory()
 * @method \Shared\Zed\LiselTime\Business\LiselTimeFacade getFacade()
 * @method \Shared\Zed\LiselTime\LiselTimeConfig getConfig()
 */
class LiselUpdateDelayEvent extends AbstractLiselTimeEvent
{
    /**
     * @var \Orm\Zed\LiselTour\Persistence\NxsStop
     */
    private $stopDetails;

    /**
     * @var \Shared\Zed\MyDeliveries\Business\MyDeliveriesFacadeInterface
     */
    private $deliveriesFacade;

    /**
     * @var \Shared\Zed\LiselStopStatus\Business\LiselStopStatusFacadeInterface
     */
    private $stopStatusFacade;

    /**
     * @var \Shared\Zed\CommunicationTracker\Business\CommunicationTrackerFacadeInterface
     */
    private $comTrackerFacade;

    /**
     * @param \Shared\Zed\MyDeliveries\Business\MyDeliveriesFacadeInterface $deliveriesFacade
     * @param \Shared\Zed\LiselStopStatus\Business\LiselStopStatusFacadeInterface $statusFacade
     * @param \Shared\Zed\CommunicationTracker\Business\CommunicationTrackerFacadeInterface $baseFacade
     */
    public function __construct(
        MyDeliveriesFacadeInterface $deliveriesFacade,
        LiselStopStatusFacadeInterface $statusFacade,
        CommunicationTrackerFacadeInterface $baseFacade
    ) {
        $this->deliveriesFacade = $deliveriesFacade;
        $this->stopStatusFacade = $statusFacade;
        $this->comTrackerFacade = $baseFacade;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return LiselEventConstants::ETA_UPDATE_DELAY;
    }

    /**
     * @return bool
     */
    public function isResponsible(): bool
    {
        $isResponsible = false;
        $this->stopDetails = NxsStopQuery::create()->findOneByStoppId($this->getTransferData()->getStoppId());

        $nxsTimeItem = NxsTimeQuery::create()
            ->filterByStoppId($this->getTransferData()->getStoppId())
            ->findOne();

        $newTimeRange = $this->deliveriesFacade->getTimeSpan(
            new DateTime($this->getTransferData()->getEta()),
            $this->getConfig()->getEtaMinus(),
            $this->getConfig()->getEtaPlus()
        );

        if ($nxsTimeItem !== null && $this->stopDetails !== null && $this->getTransferData()->getEta() !== '') {
            $nxsStopStatus = $this->stopStatusFacade->getStopStatusByStoppId($this->getTransferData()->getStoppId());
            if ($nxsStopStatus === null) {
                $existingTimeRange = $this->deliveriesFacade->getTimeSpan(
                    $nxsTimeItem->getEta(),
                    $this->getConfig()->getEtaMinus(),
                    $this->getConfig()->getEtaPlus()
                );

                if ($newTimeRange['start']->getTimeStamp() !== $existingTimeRange['start']->getTimeStamp()) {
                    $isResponsible = true;
                }
            }
        }

        return $isResponsible;
    }

    /**
     * @param \Orm\Zed\Customer\Persistence\SpyCustomer $customer
     *
     * @return bool
     */
    public function canBeNotified(SpyCustomer $customer): bool
    {
        $canBeNotified = false;
        $comCustomerTransfer = new CommunicationCustomerTransfer();
        $comCustomerTransfer->setIdCustomer($customer->getIdCustomer());
        $comCustomerTransfer->setStoppId($this->getLiselEventTransfer()->getStoppId());
        try {
            $nxsCustCommunication = $this->comTrackerFacade->getCustomerCommunication($comCustomerTransfer);
            $delayed = $this->isDelayed($nxsCustCommunication->getEventDate());
            if ($delayed) {
                $canBeNotified = true;
                $comCustomerTransfer->setEventDate($this->getLiselEventTransfer()->getDateTime());
                $this->comTrackerFacade->addCustomerCommunication($comCustomerTransfer);
            }
        } catch (Exception $exception) {
        }
        return $canBeNotified;
    }
}
