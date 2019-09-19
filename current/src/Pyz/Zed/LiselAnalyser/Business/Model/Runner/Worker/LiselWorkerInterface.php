<?php

namespace Pyz\Zed\LiselAnalyser\Business\Model\Runner\Worker;

interface LiselWorkerInterface
{
    /**
     * @return void
     */
    public function startWorker(): void;
}
