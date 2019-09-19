<?php

namespace Pyz\Zed\LiselRequest\Persistence;

use Orm\Zed\LiselRequest\Persistence\NxsLiselRequest;
use Orm\Zed\LiselRequest\Persistence\NxsLiselRequestQuery;
use Spryker\Zed\Kernel\Persistence\AbstractQueryContainer;

/**
 * @method \Shared\Zed\LiselRequest\Persistence\LiselRequestPersistenceFactory getFactory()
 */
class LiselRequestQueryContainer extends AbstractQueryContainer implements LiselRequestQueryContainerInterface
{
    /**
     * @return \Orm\Zed\LiselRequest\Persistence\NxsLiselRequest
     */
    public function createNxsLiselRequest(): NxsLiselRequest
    {
        return $this->getFactory()->createNxsLiselRequest();
    }

    /**
     * @return \Orm\Zed\LiselRequest\Persistence\NxsLiselRequestQuery
     */
    public function queryNxsLiselRequest(): NxsLiselRequestQuery
    {
        return $this->getFactory()->createNxsLiselRequestQuery();
    }
}
