<?php

namespace PyzTest\_support;

use Generated\Shared\DataBuilder\LiselStopStatusBuilder;
use Generated\Shared\Transfer\LiselStopStatusTransfer;

class StopStatusHelper
{
    /**
     * @return \Generated\Shared\Transfer\LiselStopStatusTransfer
     */
    public function createLiselStopStatusTransfer(): LiselStopStatusTransfer
    {
        return $this->createStopStatusBuilder()->build();
    }

    /**
     * @return \Generated\Shared\DataBuilder\LiselStopStatusBuilder
     */
    private function createStopStatusBuilder(): LiselStopStatusBuilder
    {
        return new LiselStopStatusBuilder();
    }
}
