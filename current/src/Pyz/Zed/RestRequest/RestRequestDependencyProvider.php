<?php

namespace Pyz\Zed\RestRequest;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

class RestRequestDependencyProvider extends AbstractBundleDependencyProvider
{
    public const JSON_SERVICE = 'JSON_SERVICE';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container): Container
    {
        $container[self::JSON_SERVICE] = static function (Container $container) {
            return $container->getLocator()->utilEncoding()->service();
        };

        return $container;
    }
}
