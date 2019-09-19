<?php

namespace Pyz\Zed\ScenarioLock\Business;

interface ScenarioLockFacadeInterface
{
    /**
     * @param string $eventName
     * @param string $stoppId
     *
     * @return bool
     */
    public function hasLock(string $eventName, string $stoppId): bool;
    /**
     * @param string $eventName
     * @param string $stoppId
     *
     * @return bool
     */
    public function addLock(string $eventName, string $stoppId): bool;

    /**
     * @param string $eventName
     * @param string $stoppId
     *
     * @return bool
     */
    public function deleteLock(string $eventName, string $stoppId): bool;

    /**
     * @param string $stoppId
     *
     * @return bool
     */
    public function deleteLockToStoppId(string $stoppId): bool;
}
