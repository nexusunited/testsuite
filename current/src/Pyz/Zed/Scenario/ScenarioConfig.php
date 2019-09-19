<?php

namespace Pyz\Zed\Scenario;

use Pyz\Shared\Scenario\ScenarioConstants;
use Spryker\Zed\Kernel\AbstractBundleConfig;

class ScenarioConfig extends AbstractBundleConfig
{
    /**
     * @return array
     */
    public function getScenarioDefinition(): array
    {
        return $this->get(ScenarioConstants::SCENARIO_SETTINGS, []);
    }
}
