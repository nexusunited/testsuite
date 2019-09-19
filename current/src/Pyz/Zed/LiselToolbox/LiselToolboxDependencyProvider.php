<?php

namespace Pyz\Zed\LiselToolbox;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

class LiselToolboxDependencyProvider extends AbstractBundleDependencyProvider
{
    public const FACADE_LISEL_REQUEST = 'FACADE_LISEL_REQUEST';
    public const LISEL_ANALYSER_FACADE = 'LISEL_ANALYSER_FACADE';
    public const DHL_IMPORT_FACADE = 'DHL_IMPORT_FACADE';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container): Container
    {
        $container[self::FACADE_LISEL_REQUEST] = static function (Container $container) {
            return $container->getLocator()->liselRequest()->facade();
        };

        $container[self::LISEL_ANALYSER_FACADE] = static function (Container $container) {
            return $container->getLocator()->liselAnalyser()->facade();
        };

        $container[self::DHL_IMPORT_FACADE] = static function (Container $container) {
            return $container->getLocator()->dhlImport()->facade();
        };

        return $container;
    }
}
