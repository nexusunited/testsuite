<?php

namespace Pyz\Zed\LiselTime\Persistence;

use Orm\Zed\LiselTime\Persistence\NxsTime;
use Orm\Zed\LiselTime\Persistence\NxsTimeQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;

/**
 * @method \Shared\Zed\LiselTime\Persistence\LiselTimeQueryContainer getQueryContainer()
 * @method \Shared\Zed\LiselTime\LiselTimeConfig getConfig()
 */
class LiselTimePersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Orm\Zed\LiselTime\Persistence\NxsTime
     */
    public function createNxsTime(): NxsTime
    {
        return new NxsTime();
    }

    /**
     * @return \Orm\Zed\LiselTime\Persistence\NxsTimeQuery
     */
    public function createNxsTimeQuery(): NxsTimeQuery
    {
        return NxsTimeQuery::create();
    }
}
