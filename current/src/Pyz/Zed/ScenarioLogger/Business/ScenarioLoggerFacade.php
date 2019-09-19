<?php

namespace Pyz\Zed\ScenarioLogger\Business;

use Generated\Shared\Transfer\ScenarioLogTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Shared\Zed\ScenarioLogger\Business\ScenarioLoggerBusinessFactory getFactory()
 */
class ScenarioLoggerFacade extends AbstractFacade implements ScenarioLoggerFacadeInterface
{
    /**
     * @param \Generated\Shared\Transfer\ScenarioLogTransfer $scenarioLogTransfer
     *
     * @return void
     */
    public function saveScenarioLog(ScenarioLogTransfer $scenarioLogTransfer): void
    {
        $this->getFactory()->createScenarioLoggerWriter()->saveScenarioLog($scenarioLogTransfer);
    }

    /**
     * @param int|null $limit
     * @param int|null $offset
     *
     * @return array
     */
    public function getLogs($limit = null, $offset = null): array
    {
        return $this->getFactory()->createScenarioLoggerReader()->getLogs($limit, $offset);
    }

    /**
     * @param int|null $limit
     * @param int|null $offset
     *
     * @return array
     */
    public function getLogsSummaryArray(?int $limit = null, ?int $offset = null): array
    {
        return $this->getFactory()->createScenarioLoggerReader()->getLogSummary($limit, $offset);
    }

    /**
     * @return int
     */
    public function getLogCount(): int
    {
        return $this->getFactory()->createScenarioLoggerReader()->getLogCount();
    }

    /**
     * @return int
     */
    public function getLogSummaryCount(): int
    {
        return $this->getFactory()->createScenarioLoggerReader()->getLogSummaryCount();
    }
}
