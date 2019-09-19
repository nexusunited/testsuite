<?php

namespace Pyz\Zed\LiselTour\Persistence;

use Orm\Zed\LiselTour\Persistence\NxsStopQuery;
use Orm\Zed\LiselTour\Persistence\NxsTour;
use Orm\Zed\LiselTour\Persistence\NxsTourQuery;
use Spryker\Zed\Kernel\Persistence\AbstractQueryContainer;

/**
 * @method \Shared\Zed\LiselTour\Persistence\LiselTourPersistenceFactory getFactory()
 */
class LiselTourQueryContainer extends AbstractQueryContainer implements LiselTourQueryContainerInterface
{
    /**
     * @return \Orm\Zed\LiselTour\Persistence\NxsTour
     */
    public function createNxsTour(): NxsTour
    {
        return $this->getFactory()->createNxsTour();
    }

    /**
     * @return \Orm\Zed\LiselTour\Persistence\NxsStopQuery
     */
    public function queryNxsStop(): NxsStopQuery
    {
        return $this->getFactory()->createNxsStopQuery();
    }

    /**
     * @return \Orm\Zed\LiselTour\Persistence\NxsTourQuery
     */
    public function queryNxsTour(): NxsTourQuery
    {
        return $this->getFactory()->createNxsTourQuery();
    }
}
