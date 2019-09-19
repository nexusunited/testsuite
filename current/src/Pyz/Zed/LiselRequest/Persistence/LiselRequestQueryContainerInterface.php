<?php

namespace Pyz\Zed\LiselRequest\Persistence;

use Orm\Zed\LiselRequest\Persistence\NxsLiselRequest;
use Orm\Zed\LiselRequest\Persistence\NxsLiselRequestQuery;
use Spryker\Zed\Kernel\Persistence\QueryContainer\QueryContainerInterface;

interface LiselRequestQueryContainerInterface extends QueryContainerInterface
{
    /**
     * @return \Orm\Zed\LiselRequest\Persistence\NxsLiselRequest
     */
    public function createNxsLiselRequest(): NxsLiselRequest;

    /**
     * @return \Orm\Zed\LiselRequest\Persistence\NxsLiselRequestQuery
     */
    public function queryNxsLiselRequest(): NxsLiselRequestQuery;
}
