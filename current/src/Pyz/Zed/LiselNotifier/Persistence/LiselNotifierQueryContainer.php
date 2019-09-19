<?php

namespace Pyz\Zed\LiselNotifier\Persistence;

use Orm\Zed\LiselTime\Persistence\NxsLiselEvents;
use Orm\Zed\LiselTime\Persistence\NxsLiselEventsQuery;
use Spryker\Zed\Kernel\Persistence\AbstractQueryContainer;

/**
 * @method \Shared\Zed\LiselNotifier\Persistence\LiselNotifierPersistenceFactory getFactory()
 */
class LiselNotifierQueryContainer extends AbstractQueryContainer implements LiselNotifierQueryContainerInterface
{
    /**
     * @return \Orm\Zed\LiselTime\Persistence\NxsLiselEventsQuery
     */
    public function createNxsLiselEventsQuery(): NxsLiselEventsQuery
    {
        return $this->getFactory()->createNxsLiselEventsQuery();
    }

    /**
     * @return \Orm\Zed\LiselTime\Persistence\NxsLiselEvents
     */
    public function createNxsLiselEvents(): NxsLiselEvents
    {
        return $this->getFactory()->createNxsLiselEvents();
    }
}
