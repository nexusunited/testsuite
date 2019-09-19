<?php

namespace Pyz\Zed\LiselNotifier\Business;

use Pyz\Zed\LiselEvent\Plugin\LiselEventInterface;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Shared\Zed\LiselNotifier\Business\LiselNotifierBusinessFactory getFactory()
 */
class LiselNotifierFacade extends AbstractFacade implements LiselNotifierFacadeInterface
{
    /**
     * @param \Shared\Zed\LiselEvent\Plugin\LiselEventInterface $liselEvent
     *
     * @return void
     */
    public function notify(LiselEventInterface $liselEvent): void
    {
        $this->getFactory()->createLiseLNotifySender()->notify($liselEvent);
    }
}
