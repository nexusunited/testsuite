<?php

namespace Pyz\Zed\ScenarioLock\Business;

use Pyz\Zed\ScenarioLock\Business\Model\ScenarioLockReader;
use Pyz\Zed\ScenarioLock\Business\Model\ScenarioLockWriter;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

/**
 * @method \Shared\Zed\ScenarioLock\Persistence\ScenarioLockQueryContainer getQueryContainer()
 */
class ScenarioLockBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Shared\Zed\ScenarioLock\Business\Model\ScenarioLockReader
     */
    public function createScenarioLockReader(): ScenarioLockReader
    {
        return new ScenarioLockReader($this->getQueryContainer());
    }

    /**
     * @return \Shared\Zed\ScenarioLock\Business\Model\ScenarioLockWriter
     */
    public function createScenarioLockWriter(): ScenarioLockWriter
    {
        return new ScenarioLockWriter($this->getQueryContainer(), $this->createScenarioLockReader());
    }
}
