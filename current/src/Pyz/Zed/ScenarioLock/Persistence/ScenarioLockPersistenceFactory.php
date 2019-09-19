<?php

namespace Pyz\Zed\ScenarioLock\Persistence;

use Orm\Zed\ScenarioLock\Persistence\NxsScenarioLock;
use Orm\Zed\ScenarioLock\Persistence\NxsScenarioLockQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;

/**
 * @method \Shared\Zed\ScenarioLock\Persistence\ScenarioLockQueryContainer getQueryContainer()
 */
class ScenarioLockPersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Orm\Zed\ScenarioLock\Persistence\NxsScenarioLockQuery
     */
    public function createNxsScenarioLockQuery(): NxsScenarioLockQuery
    {
        return NxsScenarioLockQuery::create();
    }

    /**
     * @return \Orm\Zed\ScenarioLock\Persistence\NxsScenarioLock
     */
    public function createNxsScenarioLock(): NxsScenarioLock
    {
        return new NxsScenarioLock();
    }
}
