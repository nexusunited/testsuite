<?php

namespace Pyz\Zed\LiselEvent;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

class LiselEventDependencyProvider extends AbstractBundleDependencyProvider
{
    public const LISEL_EVENT_PLUGINS = 'LISEL_EVENT_PLUGINS';
    public const LISEL_NOTIFIER = 'LISEL_NOTIFIER';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container): Container
    {
        $container[self::LISEL_NOTIFIER] = static function (Container $container) {
            return $container->getLocator()->liselNotifier()->facade();
        };

        return $container;
    }
}
