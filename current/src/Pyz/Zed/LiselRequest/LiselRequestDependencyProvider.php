<?php

namespace Pyz\Zed\LiselRequest;

use Pyz\Zed\LiselRequest\Plugin\LiselRequestCollection;
use Pyz\Zed\LiselStopStatus\Dependency\Plugin\LiselStopStatusRequest;
use Pyz\Zed\LiselTime\Dependency\Plugin\LiselTimeRequest;
use Pyz\Zed\LiselTour\Dependency\Plugin\LiselTourRequest;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

class LiselRequestDependencyProvider extends AbstractBundleDependencyProvider
{
    public const LISEL_REQUEST_PLUGINS = 'LISEL_REQUEST_PLUGINS';
    public const REST_REQUEST_FACADE = 'REST_REQUEST_FACADE';
    public const DATE_FORMAT_FACADE = 'DATE_FORMAT_FACADE';
    public const QUEUE_CLIENT = 'QUEUE_CLIENT';
    public const UTIL_ENCODING_SERVICE = 'UTIL_ENCODING_SERVICE';
    public const STORAGE_CLIENT = 'STORAGE_CLIENT';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container): Container
    {
        $container = $this->addQueueClient($container);
        $container = $this->addStorageClient($container);
        $container = $this->addLiselRequestPlugins($container);
        $container = $this->addRestRequestFacade($container);
        $container = $this->addDateFormatFacade($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container): Container
    {
        $container = $this->addUrlEncodingService($container);
        $container = $this->addRestRequestFacade($container);
        $container = $this->addDateFormatFacade($container);
        $container = $this->addLiselRequestPlugins($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    private function addRestRequestFacade(Container $container): Container
    {
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
    private function addLiselRequestPlugins(Container $container): Container
    {
        $container[self::LISEL_REQUEST_PLUGINS] = function () {
            return $this->getLiselRequestCollection();
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    private function addDateFormatFacade(Container $container): Container
    {
        $container[self::DATE_FORMAT_FACADE] = static function (Container $container) {
            return $container->getLocator()->dateFormat()->facade();
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    private function addQueueClient(Container $container): Container
    {
        $container[self::QUEUE_CLIENT] = static function (Container $container) {
            return $container->getLocator()->queue()->client();
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    private function addUrlEncodingService(Container $container): Container
    {
        $container[self::UTIL_ENCODING_SERVICE] = static function (Container $container) {
            return $container->getLocator()->utilEncoding()->service();
        };

        return $container;
    }

    /**
     * @return \Shared\Zed\LiselRequest\Plugin\LiselRequestCollection
     */
    private function getLiselRequestCollection(): LiselRequestCollection
    {
        $requestCollection = (new LiselRequestCollection())
            ->add(new LiselTourRequest())
            ->add(new LiselTimeRequest())
            ->add(new LiselStopStatusRequest());

        return $requestCollection;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    private function addStorageClient(Container $container): Container
    {
        $container[self::STORAGE_CLIENT] = static function (Container $container) {
            return $container->getLocator()->storage()->client();
        };

        return $container;
    }
}
