<?php

namespace Pyz\Zed\LiselAnalyser\Business\Model\Runner;

use Pyz\Zed\LiselAnalyser\Business\Model\Runner\Worker\LiselWorkerCollection;

class LiselWorkerRunner
{
    /**
     * @var \Shared\Zed\LiselAnalyser\Business\Model\Runner\Worker\LiselWorkerCollection $worker
     */
    private $worker;

    /**
     * @param \Shared\Zed\LiselAnalyser\Business\Model\Runner\Worker\LiselWorkerCollection $worker
     */
    public function __construct(LiselWorkerCollection $worker)
    {
        $this->worker = $worker;
    }

    /**
     * @return void
     */
    public function startWorker(): void
    {
        foreach ($this->worker as $worker) {
            $worker->startWorker();
        }
    }
}
