<?php

namespace Pyz\Zed\LiselAnalyser\Persistence;

use Propel\Runtime\Collection\ObjectCollection;
use Spryker\Zed\Kernel\Persistence\QueryContainer\QueryContainerInterface;

interface LiselAnalyserQueryContainerInterface extends QueryContainerInterface
{
    /**
     * @return \Orm\Zed\LiselRequest\Persistence\NxsLiselRequest[]|\Propel\Runtime\Collection\ObjectCollection
     */
    public function queryIdleStopps(): ObjectCollection;
}
