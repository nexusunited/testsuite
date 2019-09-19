<?php

namespace Pyz\Zed\LiselEvent\Plugin;

use Spryker\Shared\Kernel\Transfer\TransferInterface;

interface LiselEventHandlerInterface
{
    /**
     * @param \Shared\Zed\LiselEvent\Plugin\LiselEventCollection $collection
     * @param \Spryker\Shared\Kernel\Transfer\TransferInterface $transfer
     *
     * @return void
     */
    public function trigger(LiselEventCollection $collection, TransferInterface $transfer): void;
}
