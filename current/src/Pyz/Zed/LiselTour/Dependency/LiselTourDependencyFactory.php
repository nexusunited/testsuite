<?php

namespace Pyz\Zed\LiselTour\Dependency;

use Pyz\Zed\LiselEvent\Business\LiselEventFacadeInterface;
use Pyz\Zed\LiselEvent\Plugin\LiselEventCollection;
use Pyz\Zed\LiselTour\Dependency\Plugin\Events\LiselFirstPtaEvent;
use Pyz\Zed\LiselTour\Dependency\Plugin\Events\LiselUpdatePtaEvent;
use Pyz\Zed\LiselTour\LiselTourDependencyProvider;
use Pyz\Zed\MyDeliveries\Business\MyDeliveriesFacade;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;

/**
 * @method \Shared\Zed\LiselTour\LiselTourConfig getConfig()
 * @method \Shared\Zed\LiselTour\Persistence\LiselTourQueryContainer getQueryContainer()
 */
class LiselTourDependencyFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Shared\Zed\LiselEvent\Plugin\LiselEventCollection
     */
    public function createEventPlugins(): LiselEventCollection
    {
        return new LiselEventCollection(
            $this->createLiselFirstPtaEvent(),
            $this->createLiselUpdatePtaEvent()
        );
    }

    /**
     * @return \Shared\Zed\MyDeliveries\Business\MyDeliveriesFacade
     */
    public function getMyDeliveriesFacade(): MyDeliveriesFacade
    {
        return $this->getProvidedDependency(LiselTourDependencyProvider::MY_DELIVERY_FACADE);
    }

    /**
     * @return \Shared\Zed\LiselEvent\Business\LiselEventFacadeInterface
     */
    public function getLiselEventFacade(): LiselEventFacadeInterface
    {
        return $this->getProvidedDependency(LiselTourDependencyProvider::LISEL_EVENT_FACADE);
    }

    /**
     * @return \Shared\Zed\LiselTour\Dependency\Plugin\Events\LiselFirstPtaEvent
     */
    private function createLiselFirstPtaEvent(): LiselFirstPtaEvent
    {
        return new LiselFirstPtaEvent();
    }

    /**
     * @return \Shared\Zed\LiselTour\Dependency\Plugin\Events\LiselUpdatePtaEvent
     */
    private function createLiselUpdatePtaEvent(): LiselUpdatePtaEvent
    {
        return new LiselUpdatePtaEvent($this->getMyDeliveriesFacade());
    }
}
