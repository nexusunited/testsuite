<?php

namespace Pyz\Zed\LiselRequest\Communication\Plugin\Queue;

use Generated\Shared\Transfer\LiselRequestTransfer;
use Generated\Shared\Transfer\QueueSendMessageTransfer;
use Pyz\Shared\LiselRequest\LiselRequestConstants;
use Spryker\Client\Queue\QueueClientInterface;
use function json_encode;

class LiselRequestPublisher implements LiselRequestPublisherInterface
{
    /**
     * @var \Spryker\Client\Queue\QueueClientInterface
     */
    private $queueClient;

    /**
     * @param \Spryker\Client\Queue\QueueClientInterface $queueClient
     */
    public function __construct(QueueClientInterface $queueClient)
    {
        $this->queueClient = $queueClient;
    }

    /**
     * @param string $requestType
     * @param array $requestDetails
     *
     * @return void
     */
    public function publish(string $requestType, array $requestDetails): void
    {
        $liselRequestTransfer = $this->createNxsLiselRequestTransfer($requestType, $requestDetails);
        $queueSendTransfer = new QueueSendMessageTransfer();
        $queueSendTransfer->setBody($liselRequestTransfer->serialize());

        $this->queueClient->sendMessage(LiselRequestConstants::LISEL_REQUEST_QUEUE, $queueSendTransfer);
    }

    /**
     * @param string $requestType $requestType
     * @param array $requestDetails $requestDetails
     *
     * @return \Generated\Shared\Transfer\LiselRequestTransfer
     */
    private function createNxsLiselRequestTransfer(string $requestType, array $requestDetails): LiselRequestTransfer
    {
        $liselRequestTransfer = new LiselRequestTransfer();
        $liselRequestTransfer->setMessage(json_encode($requestDetails));
        $liselRequestTransfer->setRequestIdent($requestType);

        return $liselRequestTransfer;
    }
}
