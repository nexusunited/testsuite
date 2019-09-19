<?php

namespace Pyz\Zed\LiselRequest\Business;

use Pyz\Zed\DateFormat\Business\DateFormatFacade;
use Pyz\Zed\LiselRequest\Business\Model\LiselLastRequestTimestamp;
use Pyz\Zed\LiselRequest\Business\Model\LiselLastRequestTimestampInterface;
use Pyz\Zed\LiselRequest\Business\Model\LiselRequestWorker;
use Pyz\Zed\LiselRequest\Business\Model\LiselRequestWriter;
use Pyz\Zed\LiselRequest\Communication\Plugin\Queue\LiselRequestPublisher;
use Pyz\Zed\LiselRequest\Communication\Plugin\Queue\LiselRequestPublisherInterface;
use Pyz\Zed\LiselRequest\LiselRequestDependencyProvider;
use Pyz\Zed\LiselRequest\Plugin\LiselRequestCollection;
use Pyz\Zed\RestRequest\Business\RestRequestFacade;
use Spryker\Client\Queue\QueueClientInterface;
use Spryker\Client\Storage\StorageClientInterface;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

/**
 * @method \Shared\Zed\LiselRequest\LiselRequestConfig getConfig()
 * @method \Shared\Zed\LiselRequest\Persistence\LiselRequestQueryContainer getQueryContainer()
 */
class LiselRequestBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Shared\Zed\LiselRequest\Communication\Plugin\Queue\LiselRequestPublisherInterface
     */
    public function createLiselRequestPublisher(): LiselRequestPublisherInterface
    {
        return new LiselRequestPublisher($this->getQueueClient());
    }

    /**
     * @return \Spryker\Client\Queue\QueueClientInterface
     */
    public function getQueueClient(): QueueClientInterface
    {
        return $this->getProvidedDependency(LiselRequestDependencyProvider::QUEUE_CLIENT);
    }

    /**
     * @return \Shared\Zed\LiselRequest\Business\Model\LiselLastRequestTimestampInterface
     */
    public function createLiselLastRequestTimestamp(): LiselLastRequestTimestampInterface
    {
        return new LiselLastRequestTimestamp($this->getStorageClient());
    }

    /**
     * @return \Spryker\Client\Storage\StorageClientInterface
     */
    private function getStorageClient(): StorageClientInterface
    {
        return $this->getProvidedDependency(LiselRequestDependencyProvider::STORAGE_CLIENT);
    }

    /**
     * @return \Shared\Zed\LiselRequest\Business\Model\LiselRequestWriter
     */
    public function createLiselRequestWriter(): LiselRequestWriter
    {
        return new LiselRequestWriter($this->getQueryContainer());
    }

    /**
     * @return \Shared\Zed\LiselRequest\Business\Model\LiselRequestWorker
     */
    public function createLiselRequestWorker(): LiselRequestWorker
    {
        return new LiselRequestWorker(
            $this->getLiselRequestCollection(),
            $this->getQueryContainer(),
            $this->getRestRequestFacade(),
            $this->getDateFormatFacade(),
            $this->getConfig()
        );
    }

    /**
     * @return \Shared\Zed\LiselRequest\Plugin\LiselRequestCollection
     */
    private function getLiselRequestCollection(): LiselRequestCollection
    {
        return $this->getProvidedDependency(LiselRequestDependencyProvider::LISEL_REQUEST_PLUGINS);
    }

    /**
     * @return \Shared\Zed\RestRequest\Business\RestRequestFacade
     */
    public function getRestRequestFacade(): RestRequestFacade
    {
        return $this->getProvidedDependency(LiselRequestDependencyProvider::REST_REQUEST_FACADE);
    }

    /**
     * @return \Shared\Zed\DateFormat\Business\DateFormatFacade
     */
    public function getDateFormatFacade(): DateFormatFacade
    {
        return $this->getProvidedDependency(LiselRequestDependencyProvider::DATE_FORMAT_FACADE);
    }
}
