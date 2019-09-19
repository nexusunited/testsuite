<?php

namespace Pyz\Zed\LiselTime\Business;

use Pyz\Zed\LiselStopStatus\Business\LiselStopStatusFacadeInterface;
use Pyz\Zed\LiselTime\Business\Model\LiselTimeReader;
use Pyz\Zed\LiselTime\Business\Model\LiselTimeWriter;
use Pyz\Zed\LiselTime\LiselTimeDependencyProvider;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

/**
 * @method \Shared\Zed\LiselTime\LiselTimeConfig getConfig()
 * @method \Shared\Zed\LiselTime\Persistence\LiselTimeQueryContainer getQueryContainer()
 */
class LiselTimeBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Shared\Zed\LiselTime\Business\Model\LiselTimeWriter
     */
    public function createLiselTimeWriter(): LiselTimeWriter
    {
        return new LiselTimeWriter(
            $this->getQueryContainer(),
            $this->getLiselStopStatusFacade()
        );
    }

    /**
     * @return \Shared\Zed\LiselTime\Business\Model\LiselTimeReader
     */
    public function createLiselTimeReader(): LiselTimeReader
    {
        return new LiselTimeReader(
            $this->getQueryContainer()
        );
    }

    /**
     * @return \Shared\Zed\LiselStopStatus\Business\LiselStopStatusFacadeInterface
     */
    public function getLiselStopStatusFacade(): LiselStopStatusFacadeInterface
    {
        return $this->getProvidedDependency(LiselTimeDependencyProvider::LISEL_STOP_STATUS_FACADE);
    }
}
