<?php

namespace Pyz\Zed\LiselAnalyser\Business;

use Pyz\Zed\Cleaner\Business\CleanerFacadeInterface;
use Pyz\Zed\CommunicationTracker\Business\CommunicationTrackerFacadeInterface;
use Pyz\Zed\LiselAnalyser\Business\Model\Collector\CollectorCollection;
use Pyz\Zed\LiselAnalyser\Business\Model\Collector\IdleStopsCollector;
use Pyz\Zed\LiselAnalyser\Business\Model\Collector\UnfinishedStopsCollector;
use Pyz\Zed\LiselAnalyser\Business\Model\Runner\LiselWorkerRunner;
use Pyz\Zed\LiselAnalyser\Business\Model\Runner\Worker\LiselEventWorker;
use Pyz\Zed\LiselAnalyser\Business\Model\Runner\Worker\LiselIdleWorker;
use Pyz\Zed\LiselAnalyser\Business\Model\Runner\Worker\LiselWorkerCollection;
use Pyz\Zed\LiselAnalyser\Communication\Events\LiselUpdateBeforeDeliveryEvent;
use Pyz\Zed\LiselAnalyser\LiselAnalyserDependencyProvider;
use Pyz\Zed\LiselEvent\Business\LiselEventFacadeInterface;
use Pyz\Zed\LiselEvent\Plugin\LiselEventCollection;
use Pyz\Zed\LiselStopStatus\Business\LiselStopStatusFacadeInterface;
use Pyz\Zed\LiselTime\LiselTimeDependencyProvider;
use Pyz\Zed\MyDeliveries\Business\MyDeliveriesFacade;
use Pyz\Zed\ScenarioLock\Business\ScenarioLockFacadeInterface;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

/**
 * @method \Shared\Zed\LiselAnalyser\LiselAnalyserConfig getConfig()
 * @method \Shared\Zed\LiselAnalyser\Persistence\LiselAnalyserQueryContainer getQueryContainer()
 */
class LiselAnalyserBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Shared\Zed\LiselAnalyser\Business\Model\Runner\LiselWorkerRunner
     */
    public function createLiselWorkerRunner(): LiselWorkerRunner
    {
        return new LiselWorkerRunner($this->createLiselWorkerCollection());
    }

    /**
     * @return \Shared\Zed\LiselAnalyser\Business\Model\Runner\Worker\LiselWorkerCollection
     */
    private function createLiselWorkerCollection(): LiselWorkerCollection
    {
        return new LiselWorkerCollection(
            $this->createLiselEventWorker(),
            $this->createLiselIdleWorker()
        );
    }

    /**
     * @return \Shared\Zed\LiselAnalyser\Business\Model\Runner\Worker\LiselEventWorker
     */
    private function createLiselEventWorker(): LiselEventWorker
    {
        return new LiselEventWorker(
            $this->createDataCollectorPlugins(),
            $this->createEventPlugins(),
            $this->getLiselEventFacade()
        );
    }

    /**
     * @return \Shared\Zed\LiselAnalyser\Business\Model\Runner\Worker\LiselIdleWorker
     */
    private function createLiselIdleWorker(): LiselIdleWorker
    {
        return new LiselIdleWorker(
            $this->createIdleCollectorPlugins(),
            $this->getLiselStopStatusFacade()
        );
    }

    /**
     * @return \Shared\Zed\LiselAnalyser\Business\Model\Collector\CollectorCollection
     */
    public function createDataCollectorPlugins(): CollectorCollection
    {
        return new CollectorCollection(
            $this->createUnfinishedStopsCollector()
        );
    }

    /**
     * @return \Shared\Zed\LiselAnalyser\Business\Model\Collector\UnfinishedStopsCollector
     */
    private function createUnfinishedStopsCollector(): UnfinishedStopsCollector
    {
        return new UnfinishedStopsCollector($this->getQueryContainer());
    }

    /**
     * @return \Shared\Zed\LiselEvent\Plugin\LiselEventCollection
     */
    public function createEventPlugins(): LiselEventCollection
    {
        return new LiselEventCollection(
            $this->createLiselUpdateBeforeDeliveryEvent()
        );
    }

    /**
     * @return \Shared\Zed\LiselAnalyser\Communication\Events\LiselUpdateBeforeDeliveryEvent
     */
    private function createLiselUpdateBeforeDeliveryEvent(): LiselUpdateBeforeDeliveryEvent
    {
        return new LiselUpdateBeforeDeliveryEvent(
            $this->getMyDeliveriesFacade(),
            $this->getScenarioLockFacade(),
            $this->getCommunicationTrackerFacade()
        );
    }

    /**
     * @return \Shared\Zed\MyDeliveries\Business\MyDeliveriesFacade
     */
    public function getMyDeliveriesFacade(): MyDeliveriesFacade
    {
        return $this->getProvidedDependency(LiselAnalyserDependencyProvider::MY_DELIVERY_FACADE);
    }

    /**
     * @return \Shared\Zed\ScenarioLock\Business\ScenarioLockFacadeInterface
     */
    public function getScenarioLockFacade(): ScenarioLockFacadeInterface
    {
        return $this->getProvidedDependency(LiselAnalyserDependencyProvider::SCENARIO_LOCK_FACADE);
    }

    /**
     * @return \Shared\Zed\CommunicationTracker\Business\CommunicationTrackerFacadeInterface
     */
    public function getCommunicationTrackerFacade(): CommunicationTrackerFacadeInterface
    {
        return $this->getProvidedDependency(LiselTimeDependencyProvider::COMMUNICATION_TRACKER_FACADE);
    }

    /**
     * @return \Shared\Zed\LiselEvent\Business\LiselEventFacadeInterface
     */
    public function getLiselEventFacade(): LiselEventFacadeInterface
    {
        return $this->getProvidedDependency(LiselAnalyserDependencyProvider::LISEL_EVENT_FACADE);
    }

    /**
     * @return \Shared\Zed\LiselAnalyser\Business\Model\Collector\CollectorCollection
     */
    public function createIdleCollectorPlugins(): CollectorCollection
    {
        return new CollectorCollection(
            $this->createIdleStopsCollector()
        );
    }

    /**
     * @return \Shared\Zed\LiselAnalyser\Business\Model\Collector\IdleStopsCollector
     */
    private function createIdleStopsCollector(): IdleStopsCollector
    {
        return new IdleStopsCollector($this->getQueryContainer(), $this->getConfig());
    }

    /**
     * @return \Shared\Zed\LiselStopStatus\Business\LiselStopStatusFacadeInterface
     */
    public function getLiselStopStatusFacade(): LiselStopStatusFacadeInterface
    {
        return $this->getProvidedDependency(LiselAnalyserDependencyProvider::LISEL_STOP_STATUS_FACADE);
    }

    /**
     * @return \Shared\Zed\Cleaner\Business\CleanerFacadeInterface
     */
    public function getCleanerFacade(): CleanerFacadeInterface
    {
        return $this->getProvidedDependency(LiselAnalyserDependencyProvider::CLEANER_FACADE);
    }
}
