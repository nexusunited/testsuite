<?php

namespace Pyz\Zed\LiselAnalyser\Communication\Events;

use Generated\Shared\Transfer\CommunicationCustomerTransfer;
use Orm\Zed\Customer\Persistence\SpyCustomer;
use Pyz\Shared\LiselEvent\LiselEventConstants;
use Pyz\Zed\CommunicationTracker\Business\CommunicationTrackerFacadeInterface;
use Pyz\Zed\MyDeliveries\Business\MyDeliveriesFacadeInterface;
use Pyz\Zed\ScenarioLock\Business\ScenarioLockFacadeInterface;

/**
 * @method \Shared\Zed\LiselTime\Business\LiselTimeBusinessFactory getFactory()
 * @method \Shared\Zed\LiselTime\Business\LiselTimeFacade getFacade()
 */
class LiselUpdateBeforeDeliveryEvent extends AbstractLiselAnalyserEvent
{
    /**
     * @var \Shared\Zed\MyDeliveries\Business\MyDeliveriesFacadeInterface
     */
    private $deliveriesFacade;

    /**
     * @var \Shared\Zed\ScenarioLock\Business\ScenarioLockFacadeInterface
     */
    private $scenarioLockFacade;

    /**
     * @var \Shared\Zed\CommunicationTracker\Business\CommunicationTrackerFacadeInterface
     */
    private $comTrackerFacade;

    /**
     * @param \Shared\Zed\MyDeliveries\Business\MyDeliveriesFacadeInterface $deliveriesFacade
     * @param \Shared\Zed\ScenarioLock\Business\ScenarioLockFacadeInterface $scenarioLockFacade
     * @param \Shared\Zed\CommunicationTracker\Business\CommunicationTrackerFacadeInterface $baseFacade
     */
    public function __construct(
        MyDeliveriesFacadeInterface $deliveriesFacade,
        ScenarioLockFacadeInterface $scenarioLockFacade,
        CommunicationTrackerFacadeInterface $baseFacade
    ) {
        $this->deliveriesFacade = $deliveriesFacade;
        $this->scenarioLockFacade = $scenarioLockFacade;
        $this->comTrackerFacade = $baseFacade;
    }

    /**
     * @return bool
     */
    public function isResponsible(): bool
    {
        $isResponsible = false;
        if ($this->etaPeriodIsInNextMinutes($this->deliveriesFacade) &&
                    !$this->scenarioLockFacade->hasLock($this->getType(), $this->getTransferData()->getStoppId())) {
            $isResponsible = true;
            $this->scenarioLockFacade->addLock($this->getType(), $this->getTransferData()->getStoppId());
        }
        return $isResponsible;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return LiselEventConstants::ETA_UPDATE_BEFORE_DELIVERY;
    }

    /**
     * @param \Orm\Zed\Customer\Persistence\SpyCustomer $customer
     *
     * @return bool
     */
    public function canBeNotified(SpyCustomer $customer): bool
    {
        $comCustomerTransfer = new CommunicationCustomerTransfer();
        $comCustomerTransfer->setStoppId($this->getLiselEventTransfer()->getStoppId());
        $comCustomerTransfer->setEventDate($this->getLiselEventTransfer()->getDateTime());
        $comCustomerTransfer->setIdCustomer($customer->getIdCustomer());
        $this->comTrackerFacade->addCustomerCommunication($comCustomerTransfer);
        return parent::canBeNotified($customer);
    }
}
