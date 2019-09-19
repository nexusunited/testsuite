<?php

namespace Pyz\Zed\Scenario\Persistence;

use Orm\Zed\Scenario\Persistence\NxsScenarioQuery;
use Orm\Zed\Scenario\Persistence\NxsScenarioToSpyCustomerQuery;
use Spryker\Zed\Kernel\Persistence\AbstractQueryContainer;

/**
 * @method \Shared\Zed\Scenario\Persistence\ScenarioPersistenceFactory getFactory()
 */
class ScenarioQueryContainer extends AbstractQueryContainer
{
    /**
     * @return \Orm\Zed\Scenario\Persistence\NxsScenarioToSpyCustomerQuery
     */
    public function queryNxsScenarioToSpyCustomerQuery(): NxsScenarioToSpyCustomerQuery
    {
        return $this->getFactory()->createNxsScenarioToSpyCustomerQuery();
    }

    /**
     * @return \Orm\Zed\Scenario\Persistence\NxsScenarioQuery
     */
    public function queryNxsScenarioQuery(): NxsScenarioQuery
    {
        return $this->getFactory()->createNxsScenarioQuery();
    }
}
