<?php

namespace Pyz\Zed\LiselNotifier\Persistence;

use Orm\Zed\LiselTime\Persistence\NxsLiselEvents;
use Orm\Zed\LiselTime\Persistence\NxsLiselEventsQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;

/**
 * @method \Shared\Zed\LiselNotifier\LiselNotifierConfig getConfig()
 * @method \Shared\Zed\LiselNotifier\Persistence\LiselNotifierQueryContainer getQueryContainer()
 */
class LiselNotifierPersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Orm\Zed\LiselTime\Persistence\NxsLiselEvents
     */
    public function createNxsLiselEvents(): NxsLiselEvents
    {
        return new NxsLiselEvents();
    }

    /**
     * @return \Orm\Zed\LiselTime\Persistence\NxsLiselEventsQuery
     */
    public function createNxsLiselEventsQuery(): NxsLiselEventsQuery
    {
        return NxsLiselEventsQuery::create();
    }
}
