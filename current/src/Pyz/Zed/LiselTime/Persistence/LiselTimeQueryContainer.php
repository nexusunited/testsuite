<?php

namespace Pyz\Zed\LiselTime\Persistence;

use Orm\Zed\LiselTime\Persistence\NxsTime;
use Orm\Zed\LiselTime\Persistence\NxsTimeQuery;
use Spryker\Zed\Kernel\Persistence\AbstractQueryContainer;

/**
 * @method \Shared\Zed\LiselTime\Persistence\LiselTimePersistenceFactory getFactory()
 */
class LiselTimeQueryContainer extends AbstractQueryContainer implements LiselTimeQueryContainerInterface
{
    /**
     * @return \Orm\Zed\LiselTime\Persistence\NxsTime
     */
    public function queryNxsTime(): NxsTime
    {
        return $this->getFactory()->createNxsTime();
    }

    /**
     * @return \Orm\Zed\LiselTime\Persistence\NxsTimeQuery
     */
    public function createNxsTimeQuery(): NxsTimeQuery
    {
        return $this->getFactory()->createNxsTimeQuery();
    }

    /**
     * @param string $stoppId
     *
     * @return \Orm\Zed\LiselTime\Persistence\NxsTime|null
     */
    public function getLastTimeMessage(string $stoppId): ?NxsTime
    {
        return $this->getFactory()->createNxsTimeQuery()->filterByStoppId($stoppId)->findOne();
    }
}
