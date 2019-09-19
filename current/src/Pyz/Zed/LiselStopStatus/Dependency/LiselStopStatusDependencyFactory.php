<?php

namespace Pyz\Zed\LiselStopStatus\Dependency;

use Pyz\Zed\LiselEvent\Business\LiselEventFacadeInterface;
use Pyz\Zed\LiselEvent\Plugin\LiselEventCollection;
use Pyz\Zed\LiselStopStatus\Dependency\Plugin\Events\LiselAtaEvent;
use Pyz\Zed\LiselStopStatus\Dependency\Plugin\Events\LiselNewDeliveryStatusEvent;
use Pyz\Zed\LiselStopStatus\LiselStopStatusDependencyProvider;
use Pyz\Zed\LiselTour\Business\LiselTourFacadeInterface;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;

/**
 * @method \Shared\Zed\LiselTime\LiselTimeConfig getConfig()
 * @method \Shared\Zed\LiselTime\Persistence\LiselTimeQueryContainer getQueryContainer()
 */
class LiselStopStatusDependencyFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Shared\Zed\LiselEvent\Plugin\LiselEventCollection
     */
    public function createEventPlugins(): LiselEventCollection
    {
        return new LiselEventCollection(
            $this->createLiselAtaEvent(),
            $this->createLiselNewDeliveryStatusEvent()
        );
    }

    /**
     * @return \Shared\Zed\LiselEvent\Business\LiselEventFacadeInterface
     */
    public function getLiselEventFacade(): LiselEventFacadeInterface
    {
        return $this->getProvidedDependency(LiselStopStatusDependencyProvider::LISEL_EVENT_FACADE);
    }

    /**
     * @return \Shared\Zed\LiselTour\Business\LiselTourFacadeInterface
     */
    public function getLiselTourFacade(): LiselTourFacadeInterface
    {
        return $this->getProvidedDependency(LiselStopStatusDependencyProvider::LISEL_TOUR_FACADE);
    }

    /**
     * @return \Shared\Zed\LiselStopStatus\Dependency\Plugin\Events\LiselAtaEvent
     */
    private function createLiselAtaEvent(): LiselAtaEvent
    {
        return new LiselAtaEvent($this->getLiselTourFacade());
    }

    /**
     * @return \Shared\Zed\LiselStopStatus\Dependency\Plugin\Events\LiselNewDeliveryStatusEvent
     */
    private function createLiselNewDeliveryStatusEvent(): LiselNewDeliveryStatusEvent
    {
        return new LiselNewDeliveryStatusEvent($this->getLiselTourFacade());
    }
}
