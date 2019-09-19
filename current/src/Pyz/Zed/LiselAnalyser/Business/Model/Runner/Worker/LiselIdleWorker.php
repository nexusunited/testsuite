<?php

namespace Pyz\Zed\LiselAnalyser\Business\Model\Runner\Worker;

use Pyz\Zed\LiselAnalyser\Business\Model\Collector\CollectorCollection;
use Pyz\Zed\LiselStopStatus\Business\LiselStopStatusFacadeInterface;

class LiselIdleWorker implements LiselWorkerInterface
{
    /**
     * @var \Shared\Zed\LiselAnalyser\Business\Model\Collector\CollectorCollection $collector
     */
    private $collector;

    /**
     * @var \Shared\Zed\LiselStopStatus\Business\LiselStopStatusFacadeInterface
     */
    private $stopStatusFacade;

    /**
     * @param \Shared\Zed\LiselAnalyser\Business\Model\Collector\CollectorCollection $collector
     * @param \Shared\Zed\LiselStopStatus\Business\LiselStopStatusFacadeInterface $stopStatusFacade
     */
    public function __construct(CollectorCollection $collector, LiselStopStatusFacadeInterface $stopStatusFacade)
    {
        $this->collector = $collector;
        $this->stopStatusFacade = $stopStatusFacade;
    }

    /**
     * @return void
     */
    public function startWorker(): void
    {
        foreach ($this->collector as $liselDataCollector) {
            foreach ($liselDataCollector->collect() as $liselEventTransfer) {
                $this->stopStatusFacade->toggleIdle($liselEventTransfer->getStoppId(), true);
            }
        }
    }
}
