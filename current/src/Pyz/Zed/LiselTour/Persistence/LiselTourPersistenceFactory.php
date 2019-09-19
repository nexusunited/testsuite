<?php

namespace Pyz\Zed\LiselTour\Persistence;

use Orm\Zed\LiselTour\Persistence\NxsStopQuery;
use Orm\Zed\LiselTour\Persistence\NxsTour;
use Orm\Zed\LiselTour\Persistence\NxsTourQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;

/**
 * @method \Shared\Zed\LiselTour\Persistence\LiselTourQueryContainer getQueryContainer()
 * @method \Shared\Zed\LiselTour\LiselTourConfig getConfig()
 */
class LiselTourPersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Orm\Zed\LiselTour\Persistence\NxsTour
     */
    public function createNxsTour(): NxsTour
    {
        return new NxsTour();
    }

    /**
     * @return \Orm\Zed\LiselTour\Persistence\NxsStopQuery
     */
    public function createNxsStopQuery(): NxsStopQuery
    {
        return NxsStopQuery::create();
    }

    /**
     * @return \Orm\Zed\LiselTour\Persistence\NxsTourQuery
     */
    public function createNxsTourQuery(): NxsTourQuery
    {
        return NxsTourQuery::create();
    }
}
