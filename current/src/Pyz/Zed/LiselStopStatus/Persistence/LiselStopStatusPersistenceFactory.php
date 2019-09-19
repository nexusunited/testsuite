<?php

namespace Pyz\Zed\LiselStopStatus\Persistence;

use Orm\Zed\LiselStopStatus\Persistence\NxsStopStatusQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;

/**
 * @method \Shared\Zed\LiselStopStatus\Persistence\LiselStopStatusQueryContainer getQueryContainer()
 */
class LiselStopStatusPersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Orm\Zed\LiselStopStatus\Persistence\NxsStopStatusQuery
     */
    public function createNxsStopStatusQuery(): NxsStopStatusQuery
    {
        return NxsStopStatusQuery::create();
    }
}
