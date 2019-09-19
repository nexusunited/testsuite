<?php

namespace Pyz\Zed\LiselAnalyser\Business;

interface LiselAnalyserFacadeInterface
{
    /**
     * @return void
     */
    public function startWorker(): void;
}
