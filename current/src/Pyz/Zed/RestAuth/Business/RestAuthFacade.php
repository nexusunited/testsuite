<?php

namespace Pyz\Zed\RestAuth\Business;

use Spryker\Zed\Kernel\Business\AbstractFacade;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Shared\Zed\RestAuth\Business\RestAuthBusinessFactory getFactory()
 */
class RestAuthFacade extends AbstractFacade implements RestAuthFacadeInterface
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request $request
     *
     * @return bool
     */
    public function isAuth(Request $request): bool
    {
        return $this->getFactory()->createAuthProcessorHandler()->isAuth($request);
    }
}
