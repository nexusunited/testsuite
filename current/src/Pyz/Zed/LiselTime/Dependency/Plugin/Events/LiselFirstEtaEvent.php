<?php

namespace Pyz\Zed\LiselTime\Dependency\Plugin\Events;

use Generated\Shared\Transfer\CommunicationCustomerTransfer;
use Orm\Zed\Customer\Persistence\SpyCustomer;
use Orm\Zed\LiselTime\Persistence\NxsTimeQuery;
use Pyz\Shared\LiselEvent\LiselEventConstants;
use Pyz\Zed\CommunicationTracker\Business\CommunicationTrackerFacadeInterface;

/**
 * @method \Shared\Zed\LiselTime\Business\LiselTimeBusinessFactory getFactory()
 * @method \Shared\Zed\LiselTime\Business\LiselTimeFacade getFacade()
 */
class LiselFirstEtaEvent extends AbstractLiselTimeEvent
{
    /**
     * @var \Shared\Zed\CommunicationTracker\Business\CommunicationTrackerFacadeInterface
     */
    private $comTrackerFacade;

    /**
     * @param \Shared\Zed\CommunicationTracker\Business\CommunicationTrackerFacadeInterface $baseFacade
     */
    public function __construct(CommunicationTrackerFacadeInterface $baseFacade)
    {
        $this->comTrackerFacade = $baseFacade;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return LiselEventConstants::FIRST_ETA;
    }

    /**
     * @return bool
     */
    public function isResponsible(): bool
    {
        $nxsTimeItem = NxsTimeQuery::create()
            ->filterByStoppId($this->getTransferData()->getStoppId())
            ->findOne();

        return $nxsTimeItem === null &&
            ($this->getTransferData()->getEta() !== '' && $this->getTransferData()->getEta() !== null);
    }

    /**
     * @param \Orm\Zed\Customer\Persistence\SpyCustomer $customer $customer
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
