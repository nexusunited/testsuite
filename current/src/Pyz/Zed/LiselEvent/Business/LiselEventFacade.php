<?php

namespace Pyz\Zed\LiselEvent\Business;

use Pyz\Zed\LiselEvent\Plugin\LiselEventCollection;
use Spryker\Shared\Kernel\Transfer\TransferInterface;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Shared\Zed\LiselEvent\Business\LiselEventBusinessFactory getFactory()
 */
class LiselEventFacade extends AbstractFacade implements LiselEventFacadeInterface
{
    /**
     * @param \Shared\Zed\LiselEvent\Plugin\LiselEventCollection $eventCollection
     * @param \Spryker\Shared\Kernel\Transfer\TransferInterface $transferInterface
     *
     * @return void
     */
    public function trigger(LiselEventCollection $eventCollection, TransferInterface $transferInterface): void
    {
        $this->getFactory()->createLiselEventHandler()->trigger($eventCollection, $transferInterface);
    }
}
