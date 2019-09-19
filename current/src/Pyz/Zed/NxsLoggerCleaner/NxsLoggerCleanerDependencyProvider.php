<?php

namespace Pyz\Zed\NxsLoggerCleaner;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

class NxsLoggerCleanerDependencyProvider extends AbstractBundleDependencyProvider
{
    public const NXS_LOGGER_FACADE = 'NXS_LOGGER_FACADE';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container): Container
    {
        $this->addNxsLoggerFacade($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    private function addNxsLoggerFacade(Container $container): Container
    {
        $container[self::NXS_LOGGER_FACADE] = static function (Container $container) {
            return $container->getLocator()->nxsLogger()->facade();
        };

        return $container;
    }
}
