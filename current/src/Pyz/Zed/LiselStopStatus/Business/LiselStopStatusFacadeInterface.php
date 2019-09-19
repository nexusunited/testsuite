<?php

namespace Pyz\Zed\LiselStopStatus\Business;

use Generated\Shared\Transfer\NxsStopStatusEntityTransfer;

interface LiselStopStatusFacadeInterface
{
    /**
     * @param array $requestDetails
     *
     * @return bool
     */
    public function storeRequest(array $requestDetails): bool;

    /**
     * @param string $stoppId $stoppId
     *
     * @return \Generated\Shared\Transfer\NxsStopStatusEntityTransfer|null
     */
    public function getStopStatusByStoppId(string $stoppId): ?NxsStopStatusEntityTransfer;

    /**
     * @param string $stoppId
     * @param bool $isIdle
     *
     * @return bool
     */
    public function toggleIdle(string $stoppId, bool $isIdle): bool;
}
