<?php

namespace Pyz\Zed\LiselRequest\Persistence;

use Orm\Zed\LiselRequest\Persistence\NxsLiselRequest;
use Orm\Zed\LiselRequest\Persistence\NxsLiselRequestQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;

/**
 * @method \Shared\Zed\LiselRequest\LiselRequestConfig getConfig()
 * @method \Shared\Zed\LiselRequest\Persistence\LiselRequestQueryContainer getQueryContainer()
 */
class LiselRequestPersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Orm\Zed\LiselRequest\Persistence\NxsLiselRequest
     */
    public function createNxsLiselRequest(): NxsLiselRequest
    {
        return new NxsLiselRequest();
    }

    /**
     * @return \Orm\Zed\LiselRequest\Persistence\NxsLiselRequestQuery
     */
    public function createNxsLiselRequestQuery(): NxsLiselRequestQuery
    {
        return new NxsLiselRequestQuery();
    }
}
