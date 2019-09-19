<?php

namespace Pyz\Zed\LiselTime\Persistence;

use Generated\Shared\Transfer\LiselTimeTransfer;

interface LiselTimeRepositoryInterface
{
    /**
     * @param string $stoppId
     *
     * @return \Generated\Shared\Transfer\LiselTimeTransfer|null
     */
    public function getLiselTimeByStoppId(string $stoppId): ?LiselTimeTransfer;
}
