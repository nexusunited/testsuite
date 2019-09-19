<?php

namespace Pyz\Zed\LiselRequest\Business\Model;

use Pyz\Shared\LiselRequest\LiselRequestConstants;
use Spryker\Client\Storage\StorageClientInterface;

class LiselLastRequestTimestamp implements LiselLastRequestTimestampInterface
{
    /**
     * @var \Spryker\Client\Storage\StorageClientInterface
     */
    private $storageClient;

    /**
     * @param \Spryker\Client\Storage\StorageClientInterface $storageClient
     */
    public function __construct(StorageClientInterface $storageClient)
    {
        $this->storageClient = $storageClient;
    }

    /**
     * @return void
     */
    public function setTimestamp(): void
    {
        $this->storageClient->set(
            LiselRequestConstants::LISEL_REQUEST_TIMESTAMP_STORAGE_KEY,
            time()
        );
    }

    /**
     * @return int|null
     */
    public function getTimestamp(): ?int
    {
        return $this->storageClient->get(LiselRequestConstants::LISEL_REQUEST_TIMESTAMP_STORAGE_KEY);
    }
}
