<?php

namespace Pyz\Zed\Scenario;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

class ScenarioDependencyProvider extends AbstractBundleDependencyProvider
{
    public const FACADE_MAIL = 'mail facade';
    public const FACADE_COMMUNICATION_BASE = 'communication base facade';
    public const FACADE_SCENARIO_LOGGER = 'FACADE_SCENARIO_LOGGER';
    public const COMMUNICATION_TRACKER_FACADE = 'COMMUNICATION_TRACKER_FACADE';
    public const FACADE_CUSTOMER = 'FACADE_CUSTOMER';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container): Container
    {
        $container[static::FACADE_MAIL] = static function (Container $container) {
            return $container->getLocator()->mail()->facade();
        };

        $container[static::FACADE_COMMUNICATION_BASE] = static function (Container $container) {
            return $container->getLocator()->communicationBase()->facade();
        };

        $container[static::FACADE_SCENARIO_LOGGER] = static function (Container $container) {
            return $container->getLocator()->scenarioLogger()->facade();
        };

        $container[static::FACADE_CUSTOMER] = static function (Container $container) {
            return $container->getLocator()->customer()->facade();
        };

        return $container;
    }
}
