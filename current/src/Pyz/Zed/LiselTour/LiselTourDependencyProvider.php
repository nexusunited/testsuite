<?php

namespace Pyz\Zed\LiselTour;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

class LiselTourDependencyProvider extends AbstractBundleDependencyProvider
{
    public const MY_DELIVERY_FACADE = 'my_delivery_facade';
    public const LISEL_EVENT_FACADE = 'LISEL_EVENT_FACADE';
    public const LISEL_STOP_STATUS_FACADE = 'LISEL_STOP_STATUS_FACADE';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container)
    {
        $container[self::LISEL_STOP_STATUS_FACADE] = static function (Container $container) {
            return $container->getLocator()->liselStopStatus()->facade();
        };
        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container): Container
    {
        $container[self::MY_DELIVERY_FACADE] = static function (Container $container) {
            return $container->getLocator()->myDeliveries()->facade();
        };

        $container[self::LISEL_EVENT_FACADE] = static function (Container $container) {
            return $container->getLocator()->liselEvent()->facade();
        };

        return $container;
    }
}
