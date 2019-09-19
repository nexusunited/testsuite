<?php

namespace Pyz\Zed\LiselRequest\Communication;

use Pyz\Zed\DateFormat\Business\DateFormatFacadeInterface;
use Pyz\Zed\LiselRequest\LiselRequestDependencyProvider;
use Pyz\Zed\LiselRequest\Plugin\LiselRequestCollection;
use Pyz\Zed\RestRequest\Business\RestRequestFacadeInterface;
use Spryker\Service\UtilEncoding\UtilEncodingServiceInterface;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;

/**
 * @method \Shared\Zed\LiselRequest\LiselRequestConfig getConfig()
 * @method \Shared\Zed\LiselRequest\Persistence\LiselRequestQueryContainer getQueryContainer()
 */
class LiselRequestCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Service\UtilEncoding\UtilEncodingServiceInterface
     */
    public function getUtilEncodingService(): UtilEncodingServiceInterface
    {
        return $this->getProvidedDependency(LiselRequestDependencyProvider::UTIL_ENCODING_SERVICE);
    }

    /**
     * @return \Shared\Zed\LiselRequest\Plugin\LiselRequestCollection
     */
    public function getLiselRequestCollection(): LiselRequestCollection
    {
        return $this->getProvidedDependency(LiselRequestDependencyProvider::LISEL_REQUEST_PLUGINS);
    }

    /**
     * @return \Shared\Zed\RestRequest\Business\RestRequestFacadeInterface
     */
    public function getRestRequestFacade(): RestRequestFacadeInterface
    {
        return $this->getProvidedDependency(LiselRequestDependencyProvider::REST_REQUEST_FACADE);
    }

    /**
     * @return \Shared\Zed\DateFormat\Business\DateFormatFacadeInterface
     */
    public function getDateFormatFacade(): DateFormatFacadeInterface
    {
        return $this->getProvidedDependency(LiselRequestDependencyProvider::DATE_FORMAT_FACADE);
    }
}
