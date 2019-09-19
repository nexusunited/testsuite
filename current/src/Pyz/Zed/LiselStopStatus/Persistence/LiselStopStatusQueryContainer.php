<?php

namespace Pyz\Zed\LiselStopStatus\Persistence;

use Orm\Zed\LiselStopStatus\Persistence\NxsStopStatusQuery;
use Spryker\Zed\Kernel\Persistence\AbstractQueryContainer;

/**
 * @method \Shared\Zed\LiselStopStatus\Persistence\LiselStopStatusPersistenceFactory getFactory()
 */
class LiselStopStatusQueryContainer extends AbstractQueryContainer
{
    /**
     * @return \Orm\Zed\LiselStopStatus\Persistence\NxsStopStatusQuery
     */
    public function queryNxsStopStatusQuery(): NxsStopStatusQuery
    {
        return $this->getFactory()->createNxsStopStatusQuery();
    }
}
