<?php

namespace Pyz\Zed\ScenarioLock\Business\Model;

use Pyz\Zed\ScenarioLock\Persistence\ScenarioLockQueryContainerInterface;

class ScenarioLockWriter
{
    /**
     * @var \Shared\Zed\ScenarioLock\Persistence\ScenarioLockQueryContainerInterface
     */
    private $container;

    /**
     * @var \Shared\Zed\ScenarioLock\Business\Model\ScenarioLockReader
     */
    private $reader;

    /**
     * @param \Shared\Zed\ScenarioLock\Persistence\ScenarioLockQueryContainerInterface $container
     * @param \Shared\Zed\ScenarioLock\Business\Model\ScenarioLockReader $reader
     */
    public function __construct(ScenarioLockQueryContainerInterface $container, ScenarioLockReader $reader)
    {
        $this->container = $container;
        $this->reader = $reader;
    }

    /**
     * @param string $eventName
     * @param string $stoppId $stoppId
     *
     * @return bool
     */
    public function addLock(string $eventName, string $stoppId): bool
    {
        return $this
            ->container
            ->createNxsScenarioLock()
            ->setStoppId($stoppId)
            ->setNxsEvent($eventName)
            ->save() > 0;
    }

    /**
     * @param string $eventName
     * @param string $stoppId $stoppId
     *
     * @return bool
     */
    public function deleteLock(string $eventName, string $stoppId): bool
    {
        $deleted = false;
        if ($this->reader->hasLock($eventName, $stoppId)) {
            $lock = $this->reader->getLock($eventName, $stoppId);
            if ($lock !== null) {
                $lock->delete();
                $deleted = true;
            }
        }
        return $deleted;
    }

    /**
     * @param string $stoppId $stoppId
     *
     * @return bool
     */
    public function deleteLockToStoppId(string $stoppId): bool
    {
        $deleted = false;
        $lock = $this->reader->getLockByStoppId($stoppId);
        if ($lock !== null) {
            $lock->delete();
            $deleted = true;
        }
        return $deleted;
    }
}
