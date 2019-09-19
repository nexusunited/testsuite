<?php

namespace Pyz\Zed\LiselEvent\Plugin;

use Generated\Shared\Transfer\LiselEventTransfer;
use Orm\Zed\Customer\Persistence\SpyCustomer;
use Spryker\Shared\Kernel\Transfer\TransferInterface;

interface LiselEventInterface
{
    /**
     * @return bool
     */
    public function isResponsible(): bool;

    /**
     * @return string
     */
    public function getType(): string;

    /**
     * @param \Spryker\Shared\Kernel\Transfer\TransferInterface $transfer
     *
     * @return void
     */
    public function setTransferData(TransferInterface $transfer): void;

    /**
     * @return \Spryker\Shared\Kernel\Transfer\TransferInterface
     */
    public function getTransferData();

    /**
     * @return \Generated\Shared\Transfer\LiselEventTransfer
     */
    public function getLiselEventTransfer(): LiselEventTransfer;

    /**
     * @param \Orm\Zed\Customer\Persistence\SpyCustomer $spyCustomer
     *
     * @return bool
     */
    public function canBeNotified(SpyCustomer $spyCustomer): bool;
}
