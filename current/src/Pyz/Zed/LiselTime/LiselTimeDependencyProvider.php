<?php

namespace Pyz\Zed\LiselTime;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

class LiselTimeDependencyProvider extends AbstractBundleDependencyProvider
{
    public const MY_DELIVERY_FACADE = 'LISEL_TIME_FACADE';
    public const LISEL_STOP_STATUS_FACADE = 'LISEL_STOP_STATUS_FACADE';
    public const LISEL_EVENT_FACADE = 'LISEL_EVENT_FACADE';
    public const SCENARIO_LOCK_FACADE = 'SCENARIO_LOCK_FACADE';
    public const COMMUNICATION_TRACKER_FACADE = 'COMMUNICATION_TRACKER_FACADE';
    public const DELIVERY_TIME_FRAME_FACADE = 'DELIVERY_TIME_FRAME_FACADE';
    public const DATE_TIME_FORMATTER_SERVICE = 'DATE_TIME_FORMATTER_SERVICE';

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

        $container[self::LISEL_STOP_STATUS_FACADE] = static function (Container $container) {
            return $container->getLocator()->liselStopStatus()->facade();
        };

        $container[self::SCENARIO_LOCK_FACADE] = static function (Container $container) {
            return $container->getLocator()->scenarioLock()->facade();
        };

        $container[self::COMMUNICATION_TRACKER_FACADE] = static function (Container $container) {
            return $container->getLocator()->communicationTracker()->facade();
        };

        $container[self::DELIVERY_TIME_FRAME_FACADE] = static function (Container $container) {
            return $container->getLocator()->deliveryTimeFrame()->facade();
        };

        $container[self::DATE_TIME_FORMATTER_SERVICE] = static function (Container $container) {
            return $container->getLocator()->dateTimeFormatter()->service();
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container): Container
    {
        $container[self::LISEL_STOP_STATUS_FACADE] = static function (Container $container) {
            return $container->getLocator()->liselStopStatus()->facade();
        };

        return $container;
    }
}
