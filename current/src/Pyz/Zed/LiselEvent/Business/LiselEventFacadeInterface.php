<?php

namespace Pyz\Zed\LiselEvent\Business;

use Pyz\Zed\LiselEvent\Plugin\LiselEventCollection;
use Spryker\Shared\Kernel\Transfer\TransferInterface;

interface LiselEventFacadeInterface
{
    /**
     * @param \Shared\Zed\LiselEvent\Plugin\LiselEventCollection $eventCollection
     * @param \Spryker\Shared\Kernel\Transfer\TransferInterface $transferInterface
     *
     * @return void
     */
    public function trigger(LiselEventCollection $eventCollection, TransferInterface $transferInterface): void;
}
