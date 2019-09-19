<?php

namespace Pyz\Zed\LiselRequest\Communication\Plugin\Queue;

use Exception;
use Generated\Shared\Transfer\LiselRequestTransfer;
use Pyz\Zed\LiselRequest\Business\Exception\LiselRequestProcessingException;
use Pyz\Zed\LiselRequest\Business\Exception\UnknownLiselRequestTypeException;
use Pyz\Zed\LiselRequest\Plugin\LiselRequestInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\Queue\Dependency\Plugin\QueueMessageProcessorPluginInterface;

/**
 * @method \Shared\Zed\LiselRequest\Communication\LiselRequestCommunicationFactory getFactory()
 * @method \Shared\Zed\LiselRequest\Business\LiselRequestFacade getFacade()
 * @method \Shared\Zed\LiselRequest\LiselRequestConfig getConfig()
 */
class LiselRequestQueueMessageProcessorPlugin extends AbstractPlugin implements QueueMessageProcessorPluginInterface
{
    /**
     * @param \Generated\Shared\Transfer\QueueReceiveMessageTransfer[] $queueMessageTransfers
     *
     * @return \Generated\Shared\Transfer\QueueReceiveMessageTransfer[]
     */
    public function processMessages(array $queueMessageTransfers): array
    {
        foreach ($queueMessageTransfers as $queueMessageTransfer) {
            $liselRequestTransfer = $this->createTransfer($queueMessageTransfer->getQueueMessage()->getBody());
            try {
                $this->process($liselRequestTransfer);
                $queueMessageTransfer->setAcknowledge(true);
            } catch (Exception $exception) {
                $queueMessageTransfer->setAcknowledge(false);
                $queueMessageTransfer->setReject(true);
                $queueMessageTransfer->setHasError(true);
            }
        }

        return $queueMessageTransfers;
    }

    /**
     * @param string $messageBody
     *
     * @return \Generated\Shared\Transfer\LiselRequestTransfer
     */
    private function createTransfer(string $messageBody): LiselRequestTransfer
    {
        $liselRequestTransfer = new LiselRequestTransfer();
        $data = $this->getFactory()->getUtilEncodingService()->decodeJson($messageBody, true);
        $liselRequestTransfer->fromArray(
            $data,
            true
        );

        return $liselRequestTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\LiselRequestTransfer $liselRequestTransfer
     *
     * @throws \Shared\Zed\LiselRequest\Business\Exception\UnknownLiselRequestTypeException
     * @throws \Shared\Zed\LiselRequest\Business\Exception\LiselRequestProcessingException
     *
     * @return void
     */
    private function process(LiselRequestTransfer $liselRequestTransfer): void
    {
        $requestType = $liselRequestTransfer->getRequestIdent();
        if ($this->requestCollectionHasRequestType($requestType) === false) {
            throw new UnknownLiselRequestTypeException($requestType);
        }

        $liselRequest = $this->getFactory()->getLiselRequestCollection()->get($requestType);

        try {
            $this->runRequest(
                $liselRequest,
                $liselRequestTransfer
            );
        } catch (Exception $e) {
            throw new LiselRequestProcessingException($e->getMessage());
        }
    }

    /**
     * @param \Shared\Zed\LiselRequest\Plugin\LiselRequestInterface $liselRequest
     * @param \Generated\Shared\Transfer\LiselRequestTransfer $liselRequestDetails
     *
     * @return void
     */
    private function runRequest(LiselRequestInterface $liselRequest, LiselRequestTransfer $liselRequestDetails): void
    {
        $requestData = $this->getFactory()
            ->getRestRequestFacade()
            ->getData($liselRequestDetails->getMessage());
        $requestData = $this->getFactory()
            ->getDateFormatFacade()
            ->formatArray($requestData, $this->getConfig()->getAutoConvertFieldNames());
        $liselRequest
            ->triggerEvents($requestData)
            ->storeRequest($requestData);
    }

    /**
     * @param string $requestType
     *
     * @return bool
     */
    private function requestCollectionHasRequestType(string $requestType): bool
    {
        return $this->getFactory()->getLiselRequestCollection()->has($requestType);
    }

    /**
     * @return int
     */
    public function getChunkSize(): int
    {
        return $this->getConfig()->getQueueMessageProcessorChunkSize();
    }
}
