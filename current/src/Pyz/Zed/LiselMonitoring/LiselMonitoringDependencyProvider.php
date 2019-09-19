<?php

namespace Pyz\Zed\LiselMonitoring;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

class LiselMonitoringDependencyProvider extends AbstractBundleDependencyProvider
{
    public const LISEL_REQUEST_FACADE = 'LISEL_REQUEST_FACADE';
    public const MAIL_FACADE = 'MAIL_FACADE';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container): Container
    {
        $this->addLiselRequestFacade($container);
        $this->addMailFacade($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return void
     */
    private function addLiselRequestFacade(Container $container): void
    {
        $container[static::LISEL_REQUEST_FACADE] = static function (Container $container) {
            return $container->getLocator()->liselRequest()->facade();
        };
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return void
     */
    private function addMailFacade(Container $container): void
    {
        $container[static::MAIL_FACADE] = static function (Container $container) {
            return $container->getLocator()->mail()->facade();
        };
    }
}
