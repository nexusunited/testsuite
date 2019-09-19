<?php

namespace Pyz\Zed\LiselEvent\Plugin;

use ArrayIterator;

class LiselEventCollection extends ArrayIterator
{
    /**
     * @param \Shared\Zed\LiselEvent\Plugin\LiselEventInterface[] $eventInterfaces
     */
    public function __construct(LiselEventInterface ...$eventInterfaces)
    {
        parent::__construct($eventInterfaces);
    }

    /**
     * @return \Shared\Zed\LiselEvent\Plugin\LiselEventInterface
     */
    public function current(): LiselEventInterface
    {
        return parent::current();
    }

    /**
     * @param object $offset $offset
     *
     * @return \Shared\Zed\LiselEvent\Plugin\LiselEventInterface
     */
    public function offsetGet($offset): LiselEventInterface
    {
        return parent::offsetGet($offset);
    }
}
