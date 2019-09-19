<?php

namespace Pyz\Zed\LiselTour\Business;

use Pyz\Zed\LiselStopStatus\Business\LiselStopStatusFacadeInterface;
use Pyz\Zed\LiselTour\Business\Model\LiselStopReader;
use Pyz\Zed\LiselTour\Business\Model\LiselTourWriter;
use Pyz\Zed\LiselTour\LiselTourDependencyProvider;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

/**
 * @method \Shared\Zed\LiselTour\LiselTourConfigInterface getConfig()
 * @method \Shared\Zed\LiselTour\Persistence\LiselTourQueryContainer getQueryContainer()
 */
class LiselTourBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Shared\Zed\LiselTour\Business\Model\LiselTourWriter
     */
    public function createLiselTourWriter(): LiselTourWriter
    {
        return new LiselTourWriter(
            $this->getQueryContainer(),
            $this->getLiselStopStatusFacade()
        );
    }

    /**
     * @return \Shared\Zed\LiselTour\Business\Model\LiselStopReader
     */
    public function createLiselStopReader(): LiselStopReader
    {
        return new LiselStopReader(
            $this->getQueryContainer()
        );
    }

    /**
     * @return \Shared\Zed\LiselStopStatus\Business\LiselStopStatusFacadeInterface
     */
    public function getLiselStopStatusFacade(): LiselStopStatusFacadeInterface
    {
        return $this->getProvidedDependency(LiselTourDependencyProvider::LISEL_STOP_STATUS_FACADE);
    }
}
