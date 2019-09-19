<?php

namespace Pyz\Zed\LiselStopStatus\Business;

use Generated\Shared\Transfer\LiselStopStatusTransfer;
use Orm\Zed\LiselStopStatus\Persistence\NxsStopStatus;
use Pyz\Zed\LiselStopStatus\Business\Model\LiselStopStatusReader;
use Pyz\Zed\LiselStopStatus\Business\Model\LiselStopStatusWriter;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

/**
 * @method \Portal\Zed\LiselStopStatus\Persistence\LiselStopStatusQueryContainer getQueryContainer()
 */
class LiselStopStatusBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Shared\Zed\LiselStopStatus\Business\Model\LiselStopStatusWriter
     */
    public function createLiselStopStatusWriter(): LiselStopStatusWriter
    {
        return new LiselStopStatusWriter(
            $this->createNxsStopStatus(),
            $this->createLiselStopStatusTransfer()
        );
    }

    /**
     * @return \Orm\Zed\LiselStopStatus\Persistence\NxsStopStatus
     */
    public function createNxsStopStatus(): NxsStopStatus
    {
        return new NxsStopStatus();
    }

    /**
     * @return \Shared\Zed\LiselStopStatus\Business\Model\LiselStopStatusReader
     */
    public function createLiselStopStatusReader(): LiselStopStatusReader
    {
        return new LiselStopStatusReader($this->getQueryContainer());
    }

    /**
     * @return \Generated\Shared\Transfer\LiselStopStatusTransfer
     */
    public function createLiselStopStatusTransfer(): LiselStopStatusTransfer
    {
        return new LiselStopStatusTransfer();
    }
}
