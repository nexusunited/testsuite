<?php

namespace Pyz\Zed\Lisel;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

class LiselDependencyProvider extends AbstractBundleDependencyProvider
{
    public const FACADE_AUTH = 'auth facade';
    public const FACADE_LISEL_TOUR = 'lisel tour facade';
    public const FACADE_LISEL_STOP_STATUS = 'lisel stop status facade';
    public const FACADE_LISEL_TIME = 'lisel time facade';
    public const FACADE_API_DOCUMENTOR = 'api documentor facade';
    public const FACADE_NXS_LOGGER = 'nxs logger facade';
    public const FACADE_LISEL_REQUEST = 'nxs lisel request facade';
    public const REST_REQUEST_FACADE = 'REST_REQUEST_FACADE';
    public const LISEL_ANALYSER_FACADE = 'LISEL_ANALYSER_FACADE';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container): Container
    {
        $container[self::FACADE_AUTH] = static function (Container $container) {
            return $container->getLocator()->restAuth()->facade();
        };

        $container[self::FACADE_NXS_LOGGER] = static function (Container $container) {
            return $container->getLocator()->nxsLogger()->facade();
        };

        $container[self::FACADE_LISEL_REQUEST] = static function (Container $container) {
            return $container->getLocator()->liselRequest()->facade();
        };

        $container[self::REST_REQUEST_FACADE] = static function (Container $container) {
            return $container->getLocator()->restRequest()->facade();
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
        $container[self::FACADE_API_DOCUMENTOR] = static function (Container $container) {
            return $container->getLocator()->apiDocumentor()->facade();
        };

        $container[self::FACADE_LISEL_REQUEST] = static function (Container $container) {
            return $container->getLocator()->liselRequest()->facade();
        };

        $container[self::FACADE_NXS_LOGGER] = static function (Container $container) {
            return $container->getLocator()->nxsLogger()->facade();
        };

        $container[self::REST_REQUEST_FACADE] = static function (Container $container) {
            return $container->getLocator()->restRequest()->facade();
        };

        $container[self::LISEL_ANALYSER_FACADE] = static function (Container $container) {
            return $container->getLocator()->liselAnalyser()->facade();
        };

        $container[self::FACADE_AUTH] = static function (Container $container) {
            return $container->getLocator()->restAuth()->facade();
        };

        return $container;
    }
}
