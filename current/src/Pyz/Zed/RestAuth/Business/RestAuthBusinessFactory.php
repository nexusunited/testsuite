<?php

namespace Pyz\Zed\RestAuth\Business;

use Pyz\Zed\RestAuth\Business\Model\Processor\AuthProcessorCollection;
use Pyz\Zed\RestAuth\Business\Model\Processor\AuthProcessorHandler;
use Pyz\Zed\RestAuth\RestAuthDependencyProvider;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

/**
 * @method \Shared\Zed\RestAuth\RestAuthConfig getConfig()
 */
class RestAuthBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Shared\Zed\RestAuth\Business\Model\Processor\AuthProcessorHandler
     */
    public function createAuthProcessorHandler(): AuthProcessorHandler
    {
        return new AuthProcessorHandler($this->getProcessorCollection());
    }

    /**
     * @return \Shared\Zed\RestAuth\Business\Model\Processor\AuthProcessorCollection
     */
    public function getProcessorCollection(): AuthProcessorCollection
    {
        return $this->getProvidedDependency(RestAuthDependencyProvider::AUTH_PROZESSOR);
    }
}
