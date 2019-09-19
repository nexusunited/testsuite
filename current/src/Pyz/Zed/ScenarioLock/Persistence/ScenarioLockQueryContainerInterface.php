<?php

namespace Pyz\Zed\ScenarioLock\Persistence;

use Orm\Zed\ScenarioLock\Persistence\NxsScenarioLock;
use Orm\Zed\ScenarioLock\Persistence\NxsScenarioLockQuery;
use Spryker\Zed\Kernel\Persistence\QueryContainer\QueryContainerInterface;

interface ScenarioLockQueryContainerInterface extends QueryContainerInterface
{
    /**
     * @return \Orm\Zed\ScenarioLock\Persistence\NxsScenarioLockQuery
     */
    public function queryNxsScenarioLockQuery(): NxsScenarioLockQuery;

    /**
     * @return \Orm\Zed\ScenarioLock\Persistence\NxsScenarioLock
     */
    public function createNxsScenarioLock(): NxsScenarioLock;
}
