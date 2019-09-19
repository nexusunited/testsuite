<?php

namespace Pyz\Zed\LiselStopStatus;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

class LiselStopStatusDependencyProvider extends AbstractBundleDependencyProvider
{
    public const LISEL_TOUR_FACADE = 'LISEL_TOUR_FACADE';
    public const LISEL_EVENT_FACADE = 'LISEL_EVENT_FACADE';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container): Container
    {
        $container[self::LISEL_EVENT_FACADE] = static function (Container $container) {
            return $container->getLocator()->liselEvent()->facade();
        };

        $container[self::LISEL_TOUR_FACADE] = static function (Container $container) {
            return $container->getLocator()->liselTour()->facade();
        };

        return $container;
    }
}
