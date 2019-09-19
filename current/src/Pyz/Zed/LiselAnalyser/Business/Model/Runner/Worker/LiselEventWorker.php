<?php

namespace Pyz\Zed\LiselAnalyser\Business\Model\Runner\Worker;

use Pyz\Zed\LiselAnalyser\Business\Model\Collector\CollectorCollection;
use Pyz\Zed\LiselEvent\Business\LiselEventFacadeInterface;
use Pyz\Zed\LiselEvent\Plugin\LiselEventCollection;

class LiselEventWorker implements LiselWorkerInterface
{
    /**
     * @var \Shared\Zed\LiselAnalyser\Business\Model\Collector\CollectorCollection $collector
     */
    private $collector;

    /**
     * @var \Shared\Zed\LiselEvent\Plugin\LiselEventCollection
     */
    private $eventPlugins;

    /**
     * @var \Shared\Zed\LiselEvent\Business\LiselEventFacadeInterface
     */
    private $eventFacade;

    /**
     * @param \Shared\Zed\LiselAnalyser\Business\Model\Collector\CollectorCollection $collector
     * @param \Shared\Zed\LiselEvent\Plugin\LiselEventCollection $eventPlugins
     * @param \Shared\Zed\LiselEvent\Business\LiselEventFacadeInterface $eventFacade
     */
    public function __construct(CollectorCollection $collector, LiselEventCollection $eventPlugins, LiselEventFacadeInterface $eventFacade)
    {
        $this->collector = $collector;
        $this->eventPlugins = $eventPlugins;
        $this->eventFacade = $eventFacade;
    }

    /**
     * @return void
     */
    public function startWorker(): void
    {
        foreach ($this->collector as $liselDataCollector) {
            foreach ($liselDataCollector->collect() as $liselEventTransfer) {
                $this->eventFacade
                    ->trigger($this->eventPlugins, $liselEventTransfer);
            }
        }
    }
}
