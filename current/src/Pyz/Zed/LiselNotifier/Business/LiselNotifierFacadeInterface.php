<?php

namespace Pyz\Zed\LiselNotifier\Business;

use Pyz\Zed\LiselEvent\Plugin\LiselEventInterface;

interface LiselNotifierFacadeInterface
{
    /**
     * @param \Shared\Zed\LiselEvent\Plugin\LiselEventInterface $liselEvent
     *
     * @return void
     */
    public function notify(LiselEventInterface $liselEvent): void;
}
