<?php

namespace Pyz\Zed\LiselAnalyser;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

class LiselAnalyserDependencyProvider extends AbstractBundleDependencyProvider
{
    public const LISEL_TIME_FACADE = 'LISEL_TIME_FACADE';
    public const CLEANER_FACADE = 'CLEANER_FACADE';
    public const LISEL_EVENT_FACADE = 'LISEL_EVENT_FACADE';
    public const MY_DELIVERY_FACADE = 'MY_DELIVERY_FACADE';
    public const SCENARIO_LOCK_FACADE = 'SCENARIO_LOCK_FACADE';
    public const COMMUNICATION_TRACKER_FACADE = 'COMMUNICATION_TRACKER_FACADE';
    public const LISEL_STOP_STATUS_FACADE = 'LISEL_STOP_STATUS_FACADE';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container): Container
    {
        $container[self::LISEL_TIME_FACADE] = static function (Container $container) {
            return $container->getLocator()->liselTime()->facade();
        };

        $container[self::LISEL_EVENT_FACADE] = static function (Container $container) {
            return $container->getLocator()->liselEvent()->facade();
        };

        $container[self::MY_DELIVERY_FACADE] = static function (Container $container) {
            return $container->getLocator()->myDeliveries()->facade();
        };

        $container[self::SCENARIO_LOCK_FACADE] = static function (Container $container) {
            return $container->getLocator()->scenarioLock()->facade();
        };

        $container[self::COMMUNICATION_TRACKER_FACADE] = static function (Container $container) {
            return $container->getLocator()->communicationTracker()->facade();
        };

        $container[self::LISEL_STOP_STATUS_FACADE] = static function (Container $container) {
            return $container->getLocator()->liselStopStatus()->facade();
        };

        $container[self::CLEANER_FACADE] = static function (Container $container) {
            return $container->getLocator()->cleaner()->facade();
        };

        return $container;
    }
}
