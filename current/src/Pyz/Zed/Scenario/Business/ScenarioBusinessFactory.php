<?php

namespace Pyz\Zed\Scenario\Business;

use Pyz\Zed\CommunicationBase\Business\CommunicationBaseFacadeInterface;
use Pyz\Zed\Scenario\Business\Model\Scenario;
use Pyz\Zed\Scenario\Business\Model\ScenarioReader;
use Pyz\Zed\Scenario\Business\Model\ScenarioWriter;
use Pyz\Zed\Scenario\ScenarioDependencyProvider;
use Pyz\Zed\ScenarioLogger\Business\ScenarioLoggerFacade;
use Spryker\Zed\Customer\Business\CustomerFacadeInterface;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

/**
 * @method \Shared\Zed\Scenario\ScenarioConfig getConfig()
 * @method \Shared\Zed\Scenario\Persistence\ScenarioQueryContainer getQueryContainer()
 */
class ScenarioBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Shared\Zed\Scenario\Business\Model\Scenario
     */
    public function createScenario(): Scenario
    {
        return new Scenario($this->getConfig(), $this->getCommunicationBaseFacade(), $this->getCustomerFacade());
    }

    /**
     * @return \Shared\Zed\Scenario\Business\Model\ScenarioReader
     */
    public function createScenarioReader(): ScenarioReader
    {
        return new ScenarioReader($this->getQueryContainer());
    }

    /**
     * @return \Shared\Zed\Scenario\Business\Model\ScenarioWriter
     */
    public function createScenarioWriter(): ScenarioWriter
    {
        return new ScenarioWriter($this->getQueryContainer(), $this->createScenarioReader());
    }

    /**
     * @return \Shared\Zed\CommunicationBase\Business\CommunicationBaseFacadeInterface
     */
    public function getCommunicationBaseFacade(): CommunicationBaseFacadeInterface
    {
        return $this->getProvidedDependency(ScenarioDependencyProvider::FACADE_COMMUNICATION_BASE);
    }

    /**
     * @return \Shared\Zed\ScenarioLogger\Business\ScenarioLoggerFacade
     */
    public function getScenarioLoggerFacade(): ScenarioLoggerFacade
    {
        return $this->getProvidedDependency(ScenarioDependencyProvider::FACADE_SCENARIO_LOGGER);
    }

    /**
     * @return \Spryker\Zed\Customer\Business\CustomerFacadeInterface
     */
    public function getCustomerFacade(): CustomerFacadeInterface
    {
        return $this->getProvidedDependency(ScenarioDependencyProvider::FACADE_CUSTOMER);
    }
}
