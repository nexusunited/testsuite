<?php

namespace Pyz\Zed\ScenarioLogger\Business;

use Generated\Shared\Transfer\ScenarioLogTransfer;
use Orm\Zed\ScenarioLogger\Persistence\NxsScenarioLogging;
use Pyz\Zed\ScenarioLogger\Persistence\ScenarioLoggerQueryContainerInterface;

class ScenarioLoggerWriter
{
    /**
     * @var \Shared\Zed\ScenarioLogger\Persistence\ScenarioLoggerQueryContainerInterface
     */
    private $scenarioLoggerQueryContainer;

    /**
     * @param \Shared\Zed\ScenarioLogger\Persistence\ScenarioLoggerQueryContainerInterface $scenarioLogQuCon
     */
    public function __construct(ScenarioLoggerQueryContainerInterface $scenarioLogQuCon)
    {
        $this->scenarioLoggerQueryContainer = $scenarioLogQuCon;
    }

    /**
     * @param \Generated\Shared\Transfer\ScenarioLogTransfer $scenarioLogTransfer
     *
     * @return \Orm\Zed\ScenarioLogger\Persistence\NxsScenarioLogging
     */
    public function saveScenarioLog(ScenarioLogTransfer $scenarioLogTransfer): NxsScenarioLogging
    {
        $scen = $this->scenarioLoggerQueryContainer
            ->queryNxsScenarioLogger();
        $scen->fromArray($scenarioLogTransfer->toArray());
        $scen->save();
        return $scen;
    }
}
