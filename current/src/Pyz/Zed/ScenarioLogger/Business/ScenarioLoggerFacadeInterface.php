<?php

namespace Pyz\Zed\ScenarioLogger\Business;

use Generated\Shared\Transfer\ScenarioLogTransfer;

interface ScenarioLoggerFacadeInterface
{
    /**
     * @param \Generated\Shared\Transfer\ScenarioLogTransfer $scenarioLogTransfer
     *
     * @return void
     */
    public function saveScenarioLog(ScenarioLogTransfer $scenarioLogTransfer): void;

    /**
     * @param int|null $limit
     * @param int|null $offset
     *
     * @return array
     */
    public function getLogs($limit = null, $offset = null): array;

    /**
     * @param int|null $limit
     * @param int|null $offset
     *
     * @return array
     */
    public function getLogsSummaryArray(?int $limit = null, ?int $offset = null): array;

    /**
     * @return int
     */
    public function getLogCount(): int;

    /**
     * @return int
     */
    public function getLogSummaryCount(): int;
}
