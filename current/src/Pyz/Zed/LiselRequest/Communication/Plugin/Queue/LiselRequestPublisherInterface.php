<?php

namespace Pyz\Zed\LiselRequest\Communication\Plugin\Queue;

interface LiselRequestPublisherInterface
{
    /**
     * @param string $requestType
     * @param array $requestDetails
     *
     * @return void
     */
    public function publish(string $requestType, array $requestDetails): void;
}
