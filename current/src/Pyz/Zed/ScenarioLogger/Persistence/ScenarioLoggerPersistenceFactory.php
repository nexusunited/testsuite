<?php

namespace Pyz\Zed\ScenarioLogger\Persistence;

use Orm\Zed\ScenarioLogger\Persistence\NxsScenarioLogging;
use Orm\Zed\ScenarioLogger\Persistence\NxsScenarioLoggingQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;

/**
 * @method \Shared\Zed\ScenarioLogger\Persistence\ScenarioLoggerQueryContainer getQueryContainer()
 */
class ScenarioLoggerPersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Orm\Zed\ScenarioLogger\Persistence\NxsScenarioLogging
     */
    public function createNxsScenarioLogger(): NxsScenarioLogging
    {
        return new NxsScenarioLogging();
    }

    /**
     * @return \Orm\Zed\ScenarioLogger\Persistence\NxsScenarioLoggingQuery
     */
    public function createNxsScenarioLoggingQuery(): NxsScenarioLoggingQuery
    {
        return NxsScenarioLoggingQuery::create();
    }
}
