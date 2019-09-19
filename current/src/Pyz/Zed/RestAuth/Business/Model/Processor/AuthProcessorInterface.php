<?php

namespace Pyz\Zed\RestAuth\Business\Model\Processor;

use Symfony\Component\HttpFoundation\Request;

interface AuthProcessorInterface
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return bool
     */
    public function isAuth(Request $request): bool;
}
