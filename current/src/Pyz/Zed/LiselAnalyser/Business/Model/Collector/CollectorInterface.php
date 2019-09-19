<?php

namespace Pyz\Zed\LiselAnalyser\Business\Model\Collector;

interface CollectorInterface
{
    /**
     * @return \Generated\Shared\Transfer\LiselEventTransfer[]
     */
    public function collect(): array;

    /**
     * @return string
     */
    public function collectorType(): string;
}
