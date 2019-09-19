<?php

namespace Pyz\Zed\LiselEvent\Plugin;

use Spryker\Shared\Kernel\Transfer\TransferInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

abstract class AbstractLiselEvent extends AbstractPlugin
{
    /**
     * @var \Generated\Shared\Transfer\LiselEventTransfer
     */
    protected $transfer;

    /**
     * @param \Spryker\Shared\Kernel\Transfer\TransferInterface $transfer
     *
     * @return void
     */
    public function setTransferData(TransferInterface $transfer): void
    {
        $this->transfer = $transfer;
    }

    /**
     * @return \Spryker\Shared\Kernel\Transfer\TransferInterface
     */
    protected function getTransferData()
    {
        return $this->transfer;
    }
}
