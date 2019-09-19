<?php

namespace Pyz\Zed\RestAuth;

use Pyz\Zed\RestAuth\Business\Model\Processor\AuthProcessorCollection;
use Pyz\Zed\RestAuth\Business\Model\Processor\BasicAuthProcessor;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

class RestAuthDependencyProvider extends AbstractBundleDependencyProvider
{
    public const AUTH_PROZESSOR = 'AUTH_PROZESSOR';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container): Container
    {
        $container[self::AUTH_PROZESSOR] = static function () {
            $processors = new AuthProcessorCollection();
            $processors->add(new BasicAuthProcessor(new RestAuthConfig()));
            return $processors;
        };
        return $container;
    }
}
