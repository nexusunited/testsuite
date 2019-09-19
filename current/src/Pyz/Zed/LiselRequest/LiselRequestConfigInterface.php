<?php

namespace Pyz\Zed\LiselRequest;

interface LiselRequestConfigInterface
{
    /**
     * @return array
     */
    public function getAutoConvertFieldNames(): array;

    /**
     * @return int
     */
    public function getQueueMessageProcessorChunkSize(): int;
}
