<?php

namespace Pyz\Zed\ScenarioLock\Persistence;

use Orm\Zed\ScenarioLock\Persistence\NxsScenarioLock;
use Orm\Zed\ScenarioLock\Persistence\NxsScenarioLockQuery;
use Spryker\Zed\Kernel\Persistence\AbstractQueryContainer;

/**
 * @method \Shared\Zed\ScenarioLock\Persistence\ScenarioLockPersistenceFactory getFactory()
 */
class ScenarioLockQueryContainer extends AbstractQueryContainer implements ScenarioLockQueryContainerInterface
{
    /**
     * @return \Orm\Zed\ScenarioLock\Persistence\NxsScenarioLockQuery
     */
    public function queryNxsScenarioLockQuery(): NxsScenarioLockQuery
    {
        return $this->getFactory()->createNxsScenarioLockQuery();
    }

    /**
     * @return \Orm\Zed\ScenarioLock\Persistence\NxsScenarioLock
     */
    public function createNxsScenarioLock(): NxsScenarioLock
    {
        return $this->getFactory()->createNxsScenarioLock();
    }
}
