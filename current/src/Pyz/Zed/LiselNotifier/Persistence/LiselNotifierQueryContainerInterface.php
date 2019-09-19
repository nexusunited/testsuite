<?php

namespace Pyz\Zed\LiselNotifier\Persistence;

use Orm\Zed\LiselTime\Persistence\NxsLiselEvents;
use Orm\Zed\LiselTime\Persistence\NxsLiselEventsQuery;

/**
 * @method \Shared\Zed\LiselNotifier\Persistence\LiselNotifierPersistenceFactory getFactory()
 */
interface LiselNotifierQueryContainerInterface
{
    /**
     * @return \Orm\Zed\LiselTime\Persistence\NxsLiselEventsQuery
     */
    public function createNxsLiselEventsQuery(): NxsLiselEventsQuery;

    /**
     * @return \Orm\Zed\LiselTime\Persistence\NxsLiselEvents
     */
    public function createNxsLiselEvents(): NxsLiselEvents;
}
