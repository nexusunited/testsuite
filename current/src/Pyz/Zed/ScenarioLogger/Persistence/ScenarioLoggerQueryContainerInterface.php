<?php

namespace Pyz\Zed\ScenarioLogger\Persistence;

use Orm\Zed\ScenarioLogger\Persistence\NxsScenarioLogging;
use Orm\Zed\ScenarioLogger\Persistence\NxsScenarioLoggingQuery;
use Spryker\Zed\Kernel\Persistence\QueryContainer\QueryContainerInterface;

interface ScenarioLoggerQueryContainerInterface extends QueryContainerInterface
{
    /**
     * @return \Orm\Zed\ScenarioLogger\Persistence\NxsScenarioLogging
     */
    public function queryNxsScenarioLogger(): NxsScenarioLogging;

    /**
     * @return \Orm\Zed\ScenarioLogger\Persistence\NxsScenarioLoggingQuery
     */
    public function queryNxsScenarioLoggingQuery(): NxsScenarioLoggingQuery;
}
