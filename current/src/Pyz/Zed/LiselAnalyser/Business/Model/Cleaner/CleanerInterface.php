<?php

namespace Pyz\Zed\LiselAnalyser\Business\Model\Cleaner;

interface CleanerInterface
{
    /**
     * @return void
     */
    public function clean(): void;

    /**
     * @return string
     */
    public function getType(): string;
}
