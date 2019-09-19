<?php

namespace Pyz\Zed\ScenarioLock\Business\Model;

use Orm\Zed\ScenarioLock\Persistence\NxsScenarioLock;
use Propel\Runtime\Collection\ObjectCollection;
use Pyz\Zed\ScenarioLock\Persistence\ScenarioLockQueryContainerInterface;

class ScenarioLockReader
{
    /**
     * @var \Shared\Zed\ScenarioLock\Persistence\ScenarioLockQueryContainerInterface
     */
    private $container;

    /**
     * @param \Shared\Zed\ScenarioLock\Persistence\ScenarioLockQueryContainerInterface $container
     */
    public function __construct(ScenarioLockQueryContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @param string $eventName
     * @param string $stoppId
     *
     * @return bool
     */
    public function hasLock(string $eventName, string $stoppId): bool
    {
        return $this->getLock($eventName, $stoppId) !== null;
    }

    /**
     * @param string $eventName
     * @param string $stoppId
     *
     * @return \Orm\Zed\ScenarioLock\Persistence\NxsScenarioLock|null
     */
    public function getLock(string $eventName, string $stoppId): ?NxsScenarioLock
    {
        return $this
            ->container
            ->queryNxsScenarioLockQuery()
            ->filterByNxsEvent($eventName)
            ->filterByStoppId($stoppId)
            ->findOne();
    }

    /**
     * @param string $stoppId
     *
     * @return \Orm\Zed\ScenarioLock\Persistence\NxsScenarioLock[]|\Propel\Runtime\Collection\ObjectCollection
     */
    public function getLockByStoppId(string $stoppId): ObjectCollection
    {
        return $this
            ->container
            ->queryNxsScenarioLockQuery()
            ->filterByStoppId($stoppId)
            ->find();
    }
}
