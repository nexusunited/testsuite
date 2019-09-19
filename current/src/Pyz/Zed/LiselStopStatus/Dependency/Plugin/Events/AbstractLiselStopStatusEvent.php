<?php

namespace Pyz\Zed\LiselStopStatus\Dependency\Plugin\Events;

use DateTime;
use Generated\Shared\Transfer\LiselEventTransfer;
use Orm\Zed\Customer\Persistence\SpyCustomer;
use Pyz\Shared\LiselStopStatus\LiselStopStatusConstants;
use Pyz\Zed\LiselEvent\Plugin\AbstractLiselEvent;
use Pyz\Zed\LiselEvent\Plugin\LiselEventInterface;
use Spryker\Shared\Kernel\Transfer\TransferInterface;

/**
 * @method \Shared\Zed\LiselStopStatus\Business\LiselStopStatusBusinessFactory getFactory()
 * @method \Shared\Zed\LiselStopStatus\Business\LiselStopStatusFacade getFacade()
 */
abstract class AbstractLiselStopStatusEvent extends AbstractLiselEvent implements LiselEventInterface
{
    /**
     * @return \Generated\Shared\Transfer\LiselEventTransfer
     */
    public function getLiselEventTransfer(): LiselEventTransfer
    {
        $transfer = new LiselEventTransfer();
        $transfer->setStoppId($this->getTransferData()->getStoppId());
        $transfer->setDateTime(new DateTime($this->getTransferData()->getAta()));
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
     * @return bool
     */
    protected function hasAta(): bool
    {
        return $this->getTransferData()->getAta() !== '';
    }

    /**
     * @return bool
     */
    protected function isDelivered(): bool
    {
        return $this->getTransferData()->getStatus() === LiselStopStatusConstants::STATUS['Delivered'];
    }
}
