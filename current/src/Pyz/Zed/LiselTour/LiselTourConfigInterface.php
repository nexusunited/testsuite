<?php

namespace Pyz\Zed\LiselTour;

interface LiselTourConfigInterface
{
    /**
     * @return string
     */
    public function getPtaPlus(): string;

    /**
     * @return string
     */
    public function getPtaMinus(): string;
}
