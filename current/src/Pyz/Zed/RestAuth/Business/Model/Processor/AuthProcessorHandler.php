<?php

namespace Pyz\Zed\RestAuth\Business\Model\Processor;

use Symfony\Component\HttpFoundation\Request;

class AuthProcessorHandler
{
    /**
     * @var \Shared\Zed\RestAuth\Business\Model\Processor\AuthProcessorCollection
     */
    private $authProcessorCollection;

    /**
     * @param \Shared\Zed\RestAuth\Business\Model\Processor\AuthProcessorCollection $authProcessorCollection
     */
    public function __construct(AuthProcessorCollection $authProcessorCollection)
    {
        $this->authProcessorCollection = $authProcessorCollection;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return bool
     */
    public function isAuth(Request $request): bool
    {
        $authIsOk = false;
        do {
            if ($this->authProcessorCollection->current()->isAuth($request)) {
                $authIsOk = true;
            }
        } while ($this->authProcessorCollection->next());
        return $authIsOk;
    }
}
