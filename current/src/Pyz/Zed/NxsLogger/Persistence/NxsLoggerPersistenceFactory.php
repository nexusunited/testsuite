<?php

namespace Pyz\Zed\NxsLogger\Persistence;

use Orm\Zed\NxsLogger\Persistence\NxsLogging;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;

/**
 * @method \Shared\Zed\NxsLogger\NxsLoggerConfig getConfig()
 */
class NxsLoggerPersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Orm\Zed\NxsLogger\Persistence\NxsLogging
     */
    public function createNxsLogging(): NxsLogging
    {
        return new NxsLogging();
    }
}
