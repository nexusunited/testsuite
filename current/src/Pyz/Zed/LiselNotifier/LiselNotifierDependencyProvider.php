<?php

namespace Pyz\Zed\LiselNotifier;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

class LiselNotifierDependencyProvider extends AbstractBundleDependencyProvider
{
    public const SCENARIO_FACADE = 'SCENARIO_FACADE';
    public const CUSTOMER_IMPORT_FACADE = 'CUSTOMER_IMPORT_FACADE';
    public const SCENARIO_LOGGER_FACADE = 'SCENARIO_LOGGER_FACADE';
    public const LISEL_TOUR_FACADE = 'LISEL_TOUR_FACADE';
    public const LISEL_TIME_FACADE = 'LISEL_TIME_FACADE';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container): Container
    {
        $container[self::SCENARIO_FACADE] = static function (Container $container) {
            return $container->getLocator()->scenario()->facade();
        };

        $container[self::CUSTOMER_IMPORT_FACADE] = static function (Container $container) {
            return $container->getLocator()->customerImport()->facade();
        };

        $container[self::SCENARIO_LOGGER_FACADE] = static function (Container $container) {
            return $container->getLocator()->scenarioLogger()->facade();
        };

        $container[self::LISEL_TOUR_FACADE] = static function (Container $container) {
            return $container->getLocator()->liselTour()->facade();
        };

        $container[self::LISEL_TIME_FACADE] = static function (Container $container) {
            return $container->getLocator()->liselTime()->facade();
        };

        return $container;
    }
}
