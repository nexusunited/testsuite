<?php

namespace PyzTest\_support;

use Orm\Zed\Configuration\Persistence\NxsConfigurationQuery;
use Orm\Zed\Configurations\Persistence\NxsConfigurations;
use Orm\Zed\Configurations\Persistence\NxsConfigurationsQuery;
use Portal\Shared\Configurations\ConfigurationsConstants;

class ConfigurationsHelper
{
    /**
     * @param array $scenarios
     *
     * @return void
     */
    public function createOrUpdatePreselectedScenarios(array $scenarios = []): void
    {
        $configuration = $this->findScenarioConfiguration();
        if ($configuration === null) {
            $configuration = NxsConfigurationQuery::create()
                ->filterByKey(ConfigurationsConstants::PRESELECTED_SCENARIOS)
                ->findOneOrCreate();
        }

        $configuration->setValue(serialize($scenarios));
        $configuration->save();
    }

    /**
     * @return void
     */
    public function deletePreselectedScenarios(): void
    {
        $configuration = $this->findScenarioConfiguration();
        if ($configuration === null) {
            return;
        }

        $configuration->delete();
    }

    /**
     * @return \Orm\Zed\Configurations\Persistence\NxsConfigurations|null
     */
    private function findScenarioConfiguration(): ?NxsConfigurations
    {
        return $this->findConfiguration(ConfigurationsConstants::PRESELECTED_SCENARIOS);
    }

    /**
     * @param string $name
     *
     * @return \Orm\Zed\Configurations\Persistence\NxsConfigurations|null
     */
    private function findConfiguration(string $name): ?NxsConfigurations
    {
        return NxsConfigurationsQuery::create()
            ->findOneByName($name);
    }
}
