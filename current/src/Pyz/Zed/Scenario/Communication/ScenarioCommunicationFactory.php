<?php

namespace Pyz\Zed\Scenario\Communication;

use Pyz\Zed\Scenario\Business\Model\ScenarioReader;
use Pyz\Zed\Scenario\Business\Model\ScenarioWriter;
use Spryker\Zed\Customer\Communication\CustomerCommunicationFactory as SprykerCustomerCommunicationFactory;

/**
 * @method \Shared\Zed\Scenario\Persistence\ScenarioQueryContainer getQueryContainer()
 * @method \Shared\Zed\Scenario\Business\ScenarioFacadeInterface getFacade()
 * @method \Shared\Zed\Scenario\ScenarioConfig getConfig()
 */
class ScenarioCommunicationFactory extends SprykerCustomerCommunicationFactory
{
    /**
     * @return \Shared\Zed\Scenario\Business\Model\ScenarioWriter
     */
    public function createScenarioWriter(): ScenarioWriter
    {
        return new ScenarioWriter($this->getQueryContainer(), $this->createScenarioReader());
    }

    /**
     * @return \Shared\Zed\Scenario\Business\Model\ScenarioReader
     */
    public function createScenarioReader(): ScenarioReader
    {
        return new ScenarioReader($this->getQueryContainer());
    }
}
