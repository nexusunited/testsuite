<?php

namespace Pyz\Zed\LiselTour\Persistence;

use Orm\Zed\LiselTour\Persistence\NxsStopQuery;
use Orm\Zed\LiselTour\Persistence\NxsTour;
use Orm\Zed\LiselTour\Persistence\NxsTourQuery;
use Spryker\Zed\Kernel\Persistence\QueryContainer\QueryContainerInterface;

interface LiselTourQueryContainerInterface extends QueryContainerInterface
{
    /**
     * @return \Orm\Zed\LiselTour\Persistence\NxsTour
     */
    public function createNxsTour(): NxsTour;

    /**
     * @return \Orm\Zed\LiselTour\Persistence\NxsStopQuery
     */
    public function queryNxsStop(): NxsStopQuery;

    /**
     * @return \Orm\Zed\LiselTour\Persistence\NxsTourQuery
     */
    public function queryNxsTour(): NxsTourQuery;
}
