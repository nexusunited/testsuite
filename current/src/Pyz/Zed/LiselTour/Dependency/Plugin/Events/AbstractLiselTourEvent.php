<?php

namespace Pyz\Zed\LiselTour\Dependency\Plugin\Events;

use DateTime;
use Generated\Shared\Transfer\LiselEventTransfer;
use Orm\Zed\Customer\Persistence\SpyCustomer;
use Pyz\Zed\LiselEvent\Plugin\AbstractLiselEvent;
use Pyz\Zed\LiselEvent\Plugin\LiselEventInterface;

/**
 * @method \Shared\Zed\LiselTour\Business\LiselTourBusinessFactory getFactory()
 * @method \Shared\Zed\LiselTour\Business\LiselTourFacade getFacade()
 * @method \Shared\Zed\LiselTour\LiselTourConfig getConfig()
 */
abstract class AbstractLiselTourEvent extends AbstractLiselEvent implements LiselEventInterface
{
    /**
     * @return \Generated\Shared\Transfer\LiselEventTransfer
     */
    public function getLiselEventTransfer(): LiselEventTransfer
    {
        $transfer = new LiselEventTransfer();
        $transfer->setStoppId($this->getTransferData()->getStoppId());
        $transfer->setDateTime(new DateTime($this->getTransferData()->getPta()));
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
}
