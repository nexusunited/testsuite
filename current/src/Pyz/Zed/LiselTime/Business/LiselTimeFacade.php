<?php

namespace Pyz\Zed\LiselTime\Business;

use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Shared\Zed\LiselTime\Business\LiselTimeBusinessFactory getFactory()
 */
class LiselTimeFacade extends AbstractFacade implements LiselTimeFacadeInterface
{
    /**
     * @param array $request $request
     *
     * @return bool
     */
    public function storeRequest(array $request): bool
    {
        return $this->getFactory()->createLiselTimeWriter()->storeRequest($request);
    }

    /**
     * @param string $stoppId
     *
     * @return string
     */
    public function getScemId(string $stoppId): string
    {
        return $this->getFactory()->createLiselTimeReader()->getScemId($stoppId);
    }
}
