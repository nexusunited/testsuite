<?php

namespace Pyz\Zed\LiselAnalyser\Persistence;

use Orm\Zed\LiselStopStatus\Persistence\Map\NxsStopStatusTableMap;
use Orm\Zed\LiselTime\Persistence\Map\NxsTimeTableMap;
use Orm\Zed\LiselTour\Persistence\Map\NxsStopTableMap;
use Orm\Zed\LiselTour\Persistence\NxsStopQuery;
use Propel\Runtime\Collection\ObjectCollection;
use Pyz\Shared\LiselStopStatus\LiselStopStatusConstants;
use Spryker\Zed\Kernel\Persistence\AbstractQueryContainer;
use Spryker\Zed\PropelOrm\Business\Runtime\ActiveQuery\Criteria;

class LiselAnalyserQueryContainer extends AbstractQueryContainer implements LiselAnalyserQueryContainerInterface
{
    /**
     * @return \Orm\Zed\LiselRequest\Persistence\NxsLiselRequest[]|\Propel\Runtime\Collection\ObjectCollection
     */
    public function queryIdleStopps(): ObjectCollection
    {
        return NxsStopQuery::create()
            ->addJoin(NxsStopTableMap::COL_STOPP_ID, NxsStopStatusTableMap::COL_STOPP_ID, Criteria::LEFT_JOIN)
            ->addJoin(NxsStopTableMap::COL_STOPP_ID, NxsTimeTableMap::COL_STOPP_ID, Criteria::LEFT_JOIN)
            ->where('(nstatus.ata IS NULL OR nstatus.status = ' . LiselStopStatusConstants::STATUS['ContinueETA'] . ') 
                                AND ntime.eta IS NOT NULL')
            ->withColumn(NxsTimeTableMap::COL_EVENT)
            ->find();
    }
}
