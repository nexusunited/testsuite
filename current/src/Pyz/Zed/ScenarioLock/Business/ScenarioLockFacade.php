<?php

namespace Pyz\Zed\ScenarioLock\Business;

use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Shared\Zed\ScenarioLock\Business\ScenarioLockBusinessFactory getFactory()
 */
class ScenarioLockFacade extends AbstractFacade implements ScenarioLockFacadeInterface
{
    /**
     * @param string $eventName
     * @param string $stoppId
     *
     * @return bool
     */
    public function hasLock(string $eventName, string $stoppId): bool
    {
        return $this->getFactory()->createScenarioLockReader()->hasLock($eventName, $stoppId);
    }

    /**
     * @param string $eventName
     * @param string $stoppId
     *
     * @return bool
     */
    public function addLock(string $eventName, string $stoppId): bool
    {
        return $this->getFactory()->createScenarioLockWriter()->addLock($eventName, $stoppId);
    }

    /**
     * @param string $eventName
     * @param string $stoppId
     *
     * @return bool
     */
    public function deleteLock(string $eventName, string $stoppId): bool
    {
        return $this->getFactory()->createScenarioLockWriter()->deleteLock($eventName, $stoppId);
    }

    /**
     * @param string $stoppId
     *
     * @return bool
     */
    public function deleteLockToStoppId(string $stoppId): bool
    {
        return $this->getFactory()->createScenarioLockWriter()->deleteLockToStoppId($stoppId);
    }
}
