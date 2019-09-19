<?php

namespace Pyz\Zed\LiselEvent\Business;

use Pyz\Zed\LiselEvent\Business\Model\LiselEventHandler;
use Pyz\Zed\LiselEvent\LiselEventDependencyProvider;
use Pyz\Zed\LiselEvent\Plugin\LiselEventHandlerInterface;
use Pyz\Zed\LiselNotifier\Business\LiselNotifierFacadeInterface;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

class LiselEventBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Shared\Zed\LiselEvent\Plugin\LiselEventHandlerInterface
     */
    public function createLiselEventHandler(): LiselEventHandlerInterface
    {
        return new LiselEventHandler($this->getLiselNotifierFacade());
    }

    /**
     * @return \Shared\Zed\LiselNotifier\Business\LiselNotifierFacadeInterface
     */
    public function getLiselNotifierFacade(): LiselNotifierFacadeInterface
    {
        return $this->getProvidedDependency(LiselEventDependencyProvider::LISEL_NOTIFIER);
    }
}
