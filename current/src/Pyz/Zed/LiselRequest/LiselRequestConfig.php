<?php

namespace Pyz\Zed\LiselRequest;

use Pyz\Shared\LiselRequest\LiselRequestConstants;
use Spryker\Zed\Kernel\AbstractBundleConfig;

class LiselRequestConfig extends AbstractBundleConfig implements LiselRequestConfigInterface
{
    public const DEFAULT_QUEUE_MESSAGE_CHUNK_SIZE = 1;

    /**
     * @return array
     */
    public function getAutoConvertFieldNames(): array
    {
        return $this->get(LiselRequestConstants::LISEL_AUTO_CONVERT, ['date']);
    }

    /**
     * @return int
     */
    public function getQueueMessageProcessorChunkSize(): int
    {
        return $this->get(LiselRequestConstants::QUEUE_MESSAGE_CHUNK_SIZE, self::DEFAULT_QUEUE_MESSAGE_CHUNK_SIZE);
    }
}
