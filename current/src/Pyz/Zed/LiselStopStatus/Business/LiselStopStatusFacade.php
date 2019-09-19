<?php

namespace Pyz\Zed\LiselStopStatus\Business;

use Generated\Shared\Transfer\NxsStopStatusEntityTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Shared\Zed\LiselStopStatus\Business\LiselStopStatusBusinessFactory getFactory()
 */
class LiselStopStatusFacade extends AbstractFacade implements LiselStopStatusFacadeInterface
{
    /**
     * @param array $requestDetails
     *
     * @return bool
     */
    public function storeRequest(array $requestDetails): bool
    {
        return $this->getFactory()->createLiselStopStatusWriter()->storeRequest($requestDetails);
    }

    /**
     * @param string $stoppId
     * @param bool $isIdle
     *
     * @return bool
     */
    public function toggleIdle(string $stoppId, bool $isIdle): bool
    {
        return $this->getFactory()->createLiselStopStatusWriter()->toggleIdle($stoppId, $isIdle);
    }

    /**
     * @param string $stoppId $stoppId
     *
     * @return \Generated\Shared\Transfer\NxsStopStatusEntityTransfer|null
     */
    public function getStopStatusByStoppId(string $stoppId): ?NxsStopStatusEntityTransfer
    {
        return $this->getFactory()->createLiselStopStatusReader()->getStopStatusByStoppId($stoppId);
    }
}
