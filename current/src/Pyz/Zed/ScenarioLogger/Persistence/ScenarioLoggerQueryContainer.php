<?php

namespace Pyz\Zed\ScenarioLogger\Persistence;

use Orm\Zed\ScenarioLogger\Persistence\NxsScenarioLogging;
use Orm\Zed\ScenarioLogger\Persistence\NxsScenarioLoggingQuery;
use Spryker\Zed\Kernel\Persistence\AbstractQueryContainer;

/**
 * @method \Shared\Zed\ScenarioLogger\Persistence\ScenarioLoggerPersistenceFactory getFactory()
 */
class ScenarioLoggerQueryContainer extends AbstractQueryContainer implements ScenarioLoggerQueryContainerInterface
{
    /**
     * @return \Orm\Zed\ScenarioLogger\Persistence\NxsScenarioLogging
     */
    public function queryNxsScenarioLogger(): NxsScenarioLogging
    {
        return $this->getFactory()->createNxsScenarioLogger();
    }

    /**
     * @return \Orm\Zed\ScenarioLogger\Persistence\NxsScenarioLoggingQuery
     */
    public function queryNxsScenarioLoggingQuery(): NxsScenarioLoggingQuery
    {
        return $this->getFactory()->createNxsScenarioLoggingQuery();
    }
}
