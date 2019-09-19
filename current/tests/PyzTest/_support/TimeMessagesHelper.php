<?php

namespace PyzTest\_support;

use Generated\Shared\DataBuilder\LiselTimeMessagesBuilder;
use Generated\Shared\Transfer\LiselTimeMessagesTransfer;

class TimeMessagesHelper
{
    /**
     * @return \Generated\Shared\Transfer\LiselTimeMessagesTransfer
     */
    public function createLiselTimeMessagesTransfer(): LiselTimeMessagesTransfer
    {
        return $this->createTimeMessagesBuilder()->withTimeMessage()->build();
    }

    /**
     * @return \Generated\Shared\DataBuilder\LiselTimeMessagesBuilder
     */
    private function createTimeMessagesBuilder()
    {
        return new LiselTimeMessagesBuilder();
    }
}
