<?php

namespace Pyz\Zed\RestAuth\Business;

use Symfony\Component\HttpFoundation\Request;

interface RestAuthFacadeInterface
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request $request
     *
     * @return bool
     */
    public function isAuth(Request $request): bool;
}
