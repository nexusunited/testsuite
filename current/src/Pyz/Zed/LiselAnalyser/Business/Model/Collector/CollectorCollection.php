<?php

namespace Pyz\Zed\LiselAnalyser\Business\Model\Collector;

use ArrayIterator;

class CollectorCollection extends ArrayIterator
{
    /**
     * @param \Shared\Zed\LiselAnalyser\Business\Model\Collector\CollectorInterface[] $collectorInterfaces
     */
    public function __construct(CollectorInterface ...$collectorInterfaces)
    {
        parent::__construct($collectorInterfaces);
    }

    /**
     * @return \Shared\Zed\LiselAnalyser\Business\Model\Collector\CollectorInterface
     */
    public function current(): CollectorInterface
    {
        return parent::current();
    }

    /**
     * @param object $offset
     *
     * @return \Shared\Zed\LiselAnalyser\Business\Model\Collector\CollectorInterface
     */
    public function offsetGet($offset): CollectorInterface
    {
        return parent::offsetGet($offset);
    }
}
