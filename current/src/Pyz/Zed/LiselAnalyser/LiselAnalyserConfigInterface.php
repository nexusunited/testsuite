<?php

namespace Pyz\Zed\LiselAnalyser;

interface LiselAnalyserConfigInterface
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

    /**
     * @return string
     */
    public function getMaxIdleTime(): string;
}
