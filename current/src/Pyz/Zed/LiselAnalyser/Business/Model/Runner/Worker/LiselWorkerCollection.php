<?php

namespace Pyz\Zed\LiselAnalyser\Business\Model\Runner\Worker;

use ArrayIterator;

class LiselWorkerCollection extends ArrayIterator
{
    /**
     * @param \Shared\Zed\LiselAnalyser\Business\Model\Runner\Worker\LiselWorkerInterface ...$collectorInterfaces
     */
    public function __construct(LiselWorkerInterface ...$collectorInterfaces)
    {
        parent::__construct($collectorInterfaces);
    }

    /**
     * @return \Shared\Zed\LiselAnalyser\Business\Model\Runner\Worker\LiselWorkerInterface
     */
    public function current(): LiselWorkerInterface
    {
        return parent::current();
    }

    /**
     * @param object $offset
     *
     * @return \Shared\Zed\LiselAnalyser\Business\Model\Runner\Worker\LiselWorkerInterface
     */
    public function offsetGet($offset): LiselWorkerInterface
    {
        return parent::offsetGet($offset);
    }
}
