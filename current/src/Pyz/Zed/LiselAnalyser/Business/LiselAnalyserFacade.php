<?php

namespace Pyz\Zed\LiselAnalyser\Business;

use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Shared\Zed\LiselAnalyser\Business\LiselAnalyserBusinessFactory getFactory()
 */
class LiselAnalyserFacade extends AbstractFacade implements LiselAnalyserFacadeInterface
{
    /**
     * @return void
     */
    public function startWorker(): void
    {
        $this->getFactory()->createLiselWorkerRunner()->startWorker();
    }
}
