<?php

namespace Pyz\Zed\Scenario\Persistence;

interface ScenarioRepositoryInterface
{
    /**
     * @return \Generated\Shared\Transfer\NxsScenarioEntityTransfer[]
     */
    public function getScenarioTransfers(): array;
}
