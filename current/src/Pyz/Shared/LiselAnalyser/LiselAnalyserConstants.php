<?php

namespace Pyz\Shared\LiselAnalyser;

interface LiselAnalyserConstants
{
    /**
     * CollectorTypes
     */
    public const UNFINISHED_STOPS_COLLECTOR = 'UNFINISHED_STOPS_COLLECTOR';
    public const IDLE_STOPS_COLLECTOR = 'IDLE_STOPS_COLLECTOR';

    /**
     * Config
     */
    public const MAX_IDLE_AGE = 'MAX_IDLE_AGE';
}
