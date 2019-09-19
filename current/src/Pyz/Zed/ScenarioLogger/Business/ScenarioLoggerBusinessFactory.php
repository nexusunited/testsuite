<?php

namespace Pyz\Zed\ScenarioLogger\Business;

use Spryker\Shared\Kernel\Store;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

/**
 * @method \Shared\Zed\ScenarioLogger\Persistence\ScenarioLoggerQueryContainer getQueryContainer()
 */
class ScenarioLoggerBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Shared\Zed\ScenarioLogger\Business\ScenarioLoggerWriter
     */
    public function createScenarioLoggerWriter(): ScenarioLoggerWriter
    {
        return new ScenarioLoggerWriter($this->getQueryContainer());
    }

    /**
     * @return \Shared\Zed\ScenarioLogger\Business\ScenarioLoggerReader
     */
    public function createScenarioLoggerReader(): ScenarioLoggerReader
    {
        return new ScenarioLoggerReader($this->getQueryContainer(), $this->getStoreName());
    }

    /**
     * @return string
     */
    private function getStoreName(): string
    {
        return Store::getInstance()->getStoreName();
    }
}
