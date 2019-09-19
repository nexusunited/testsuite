<?php

namespace Pyz\Zed\LiselTime\Dependency;

use Pyz\Service\DateTimeFormatter\DateTimeFormatterServiceInterface;
use Pyz\Zed\CommunicationTracker\Business\CommunicationTrackerFacadeInterface;
use Pyz\Zed\DeliveryTimeFrame\Business\DeliveryTimeFrameFacadeInterface;
use Pyz\Zed\LiselEvent\Business\LiselEventFacadeInterface;
use Pyz\Zed\LiselEvent\Plugin\LiselEventCollection;
use Pyz\Zed\LiselStopStatus\Business\LiselStopStatusFacadeInterface;
use Pyz\Zed\LiselTime\Dependency\Plugin\Events\LiselDeliveryTimeFrameDelayDeviationEvent;
use Pyz\Zed\LiselTime\Dependency\Plugin\Events\LiselFirstEtaEvent;
use Pyz\Zed\LiselTime\Dependency\Plugin\Events\LiselUpdateDelayEvent;
use Pyz\Zed\LiselTime\Dependency\Plugin\Events\LiselUpdateEtaEvent;
use Pyz\Zed\LiselTime\LiselTimeDependencyProvider;
use Pyz\Zed\MyDeliveries\Business\MyDeliveriesFacade;
use Pyz\Zed\ScenarioLock\Business\ScenarioLockFacadeInterface;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;

/**
 * @method \Shared\Zed\LiselTime\LiselTimeConfig getConfig()
 * @method \Shared\Zed\LiselTime\Persistence\LiselTimeQueryContainer getQueryContainer()
 * @method \Shared\Zed\LiselTime\Persistence\LiselTimeRepository getRepository()()
 */
class LiselTimeDependencyFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Shared\Zed\LiselEvent\Plugin\LiselEventCollection
     */
    public function createEventPlugins(): LiselEventCollection
    {
        return new LiselEventCollection(
            $this->createLiselFirstEtaEvent(),
            $this->createLiselUpdateEtaEvent(),
            $this->createLiselUpdateDelayEvent(),
            $this->createLiselDeliveryTimeFrameDelayDeviationEvent()
        );
    }

    /**
     * @return \Shared\Zed\MyDeliveries\Business\MyDeliveriesFacade
     */
    public function getMyDeliveriesFacade(): MyDeliveriesFacade
    {
        return $this->getProvidedDependency(LiselTimeDependencyProvider::MY_DELIVERY_FACADE);
    }

    /**
     * @return \Shared\Zed\LiselEvent\Business\LiselEventFacadeInterface
     */
    public function getLiselEventFacade(): LiselEventFacadeInterface
    {
        return $this->getProvidedDependency(LiselTimeDependencyProvider::LISEL_EVENT_FACADE);
    }

    /**
     * @return \Shared\Zed\LiselStopStatus\Business\LiselStopStatusFacadeInterface
     */
    public function getLiselStopStatusFacade(): LiselStopStatusFacadeInterface
    {
        return $this->getProvidedDependency(LiselTimeDependencyProvider::LISEL_STOP_STATUS_FACADE);
    }

    /**
     * @return \Shared\Zed\ScenarioLock\Business\ScenarioLockFacadeInterface
     */
    public function getScenarioLockFacade(): ScenarioLockFacadeInterface
    {
        return $this->getProvidedDependency(LiselTimeDependencyProvider::SCENARIO_LOCK_FACADE);
    }

    /**
     * @return \Shared\Zed\CommunicationTracker\Business\CommunicationTrackerFacadeInterface
     */
    public function getCommunicationTrackerFacade(): CommunicationTrackerFacadeInterface
    {
        return $this->getProvidedDependency(LiselTimeDependencyProvider::COMMUNICATION_TRACKER_FACADE);
    }

    /**
     * @return \Shared\Zed\DeliveryTimeFrame\Business\DeliveryTimeFrameFacadeInterface
     */
    public function getDeliveryTimeFrameFacade(): DeliveryTimeFrameFacadeInterface
    {
        return $this->getProvidedDependency(LiselTimeDependencyProvider::DELIVERY_TIME_FRAME_FACADE);
    }

    /**
     * @return \Shared\Service\DateTimeFormatter\DateTimeFormatterServiceInterface
     */
    public function getDateTimeFormatterService(): DateTimeFormatterServiceInterface
    {
        return $this->getProvidedDependency(LiselTimeDependencyProvider::DATE_TIME_FORMATTER_SERVICE);
    }

    /**
     * @return \Shared\Zed\LiselTime\Dependency\Plugin\Events\LiselFirstEtaEvent
     */
    private function createLiselFirstEtaEvent(): LiselFirstEtaEvent
    {
        return new LiselFirstEtaEvent(
            $this->getCommunicationTrackerFacade()
        );
    }

    /**
     * @return \Shared\Zed\LiselTime\Dependency\Plugin\Events\LiselUpdateEtaEvent
     */
    private function createLiselUpdateEtaEvent(): LiselUpdateEtaEvent
    {
        return new LiselUpdateEtaEvent(
            $this->getMyDeliveriesFacade(),
            $this->getLiselStopStatusFacade()
        );
    }

    /**
     * @return \Shared\Zed\LiselTime\Dependency\Plugin\Events\LiselUpdateDelayEvent
     */
    private function createLiselUpdateDelayEvent(): LiselUpdateDelayEvent
    {
        return new LiselUpdateDelayEvent(
            $this->getMyDeliveriesFacade(),
            $this->getLiselStopStatusFacade(),
            $this->getCommunicationTrackerFacade()
        );
    }

    /**
     * @return \Shared\Zed\LiselTime\Dependency\Plugin\Events\LiselDeliveryTimeFrameDelayDeviationEvent
     */
    private function createLiselDeliveryTimeFrameDelayDeviationEvent(): LiselDeliveryTimeFrameDelayDeviationEvent
    {
        return new LiselDeliveryTimeFrameDelayDeviationEvent(
            $this->getDeliveryTimeFrameFacade(),
            $this->getRepository(),
            $this->getDateTimeFormatterService()
        );
    }
}
