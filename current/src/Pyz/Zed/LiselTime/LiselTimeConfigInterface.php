<?php

namespace Pyz\Zed\LiselTime;

interface LiselTimeConfigInterface
{
    /**
     * @return int
     */
    public function getEtaUpdateShortBeforeMin(): int;
    /**
     * @return string
     */
    public function getEtaMinus(): string;

     /**
      * @return string
      */
    public function getEtaPlus(): string;
}
