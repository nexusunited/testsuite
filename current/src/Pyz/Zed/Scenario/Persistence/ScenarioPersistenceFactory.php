<?php

namespace Pyz\Zed\Scenario\Persistence;

use Orm\Zed\Scenario\Persistence\NxsScenarioQuery;
use Orm\Zed\Scenario\Persistence\NxsScenarioToSpyCustomerQuery;
use Pyz\Zed\Scenario\ScenarioDependencyProvider;
use Pyz\Zed\ScenarioLogger\Business\ScenarioLoggerFacade;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;

/**
 * @method \Shared\Zed\Scenario\ScenarioConfig getConfig()
 * @method \Shared\Zed\Scenario\Persistence\ScenarioQueryContainer getQueryContainer()
 */
class ScenarioPersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Orm\Zed\Scenario\Persistence\NxsScenarioToSpyCustomerQuery
     */
    public function createNxsScenarioToSpyCustomerQuery(): NxsScenarioToSpyCustomerQuery
    {
        return NxsScenarioToSpyCustomerQuery::create();
    }

    /**
     * @return \Orm\Zed\Scenario\Persistence\NxsScenarioQuery
     */
    public function createNxsScenarioQuery(): NxsScenarioQuery
    {
        return NxsScenarioQuery::create();
    }

    /**
     * @return \Shared\Zed\ScenarioLogger\Business\ScenarioLoggerFacade
     */
    public function getScenarioLogger(): ScenarioLoggerFacade
    {
        return $this->getProvidedDependency(ScenarioDependencyProvider::FACADE_SCENARIO_LOGGER);
    }
}
