<?php

namespace Pyz\Zed\LiselTime\Persistence;

use Orm\Zed\LiselTime\Persistence\NxsTime;
use Spryker\Zed\Kernel\Persistence\QueryContainer\QueryContainerInterface;

interface LiselTimeQueryContainerInterface extends QueryContainerInterface
{
    /**
     * @return \Orm\Zed\LiselTime\Persistence\NxsTime
     */
    public function queryNxsTime(): NxsTime;

    /**
     * @param string $stoppId
     *
     * @return \Orm\Zed\LiselTime\Persistence\NxsTime|null
     */
    public function getLastTimeMessage(string $stoppId): ?NxsTime;
}
