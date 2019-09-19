<?php

namespace Pyz\Zed\LiselRequest\Business\Model;

use Exception;
use Orm\Zed\LiselRequest\Persistence\NxsLiselRequest;
use Propel\Runtime\ActiveQuery\Criteria;
use Pyz\Zed\DateFormat\Business\DateFormatFacadeInterface;
use Pyz\Zed\LiselRequest\Business\Exception\UnknownLiselRequestTypeException;
use Pyz\Zed\LiselRequest\LiselRequestConfigInterface;
use Pyz\Zed\LiselRequest\Persistence\LiselRequestQueryContainer;
use Pyz\Zed\LiselRequest\Plugin\LiselRequestCollection;
use Pyz\Zed\LiselRequest\Plugin\LiselRequestInterface;
use Pyz\Zed\RestRequest\Business\RestRequestFacade;
use function date;

class LiselRequestWorker
{
    public const PROCCESSED_DATE_FORMAT = 'Y-m-d H:i:s';

    /**
     * @var \Shared\Zed\LiselRequest\Plugin\LiselRequestCollection
     */
    private $liselRequestCollection;

    /**
     * @var \Shared\Zed\LiselRequest\Persistence\LiselRequestQueryContainer
     */
    private $queryContainer;

    /**
     * @var \Shared\Zed\RestRequest\Business\RestRequestFacade
     */
    private $requestFacade;

    /**
     * @var \Shared\Zed\DateFormat\Business\DateFormatFacadeInterface
     */
    private $dateFormatFacade;

    /**
     * @var \Shared\Zed\LiselRequest\LiselRequestConfigInterface
     */
    private $config;

    /**
     * @param \Shared\Zed\LiselRequest\Plugin\LiselRequestCollection $collection
     * @param \Shared\Zed\LiselRequest\Persistence\LiselRequestQueryContainer $queryContainer
     * @param \Shared\Zed\RestRequest\Business\RestRequestFacade $requestFacade
     * @param \Shared\Zed\DateFormat\Business\DateFormatFacadeInterface $dateFormatFacade
     * @param \Shared\Zed\LiselRequest\LiselRequestConfigInterface $config
     */
    public function __construct(
        LiselRequestCollection $collection,
        LiselRequestQueryContainer $queryContainer,
        RestRequestFacade $requestFacade,
        DateFormatFacadeInterface $dateFormatFacade,
        LiselRequestConfigInterface $config
    ) {
        $this->liselRequestCollection = $collection;
        $this->queryContainer = $queryContainer;
        $this->requestFacade = $requestFacade;
        $this->dateFormatFacade = $dateFormatFacade;
        $this->config = $config;
    }

    /**
     * @return void
     */
    public function startWorker(): void
    {
        $unprogressedList = $this->queryContainer
            ->queryNxsLiselRequest()
            ->filterByProcessed(null, Criteria::EQUAL)
            ->find();
        $this->process($unprogressedList->getData());
    }

    /**
     * @param \Orm\Zed\LiselRequest\Persistence\NxsLiselRequest[] $nxsLiselRequests
     *
     * @throws \Shared\Zed\LiselRequest\Business\Exception\UnknownLiselRequestTypeException
     *
     * @return void
     */
    private function process(array $nxsLiselRequests): void
    {
        foreach ($nxsLiselRequests as $liselRequest) {
            $requestType = $liselRequest->getRequestIdent();
            if ($this->liselRequestCollection->has($requestType)) {
                try {
                    $this->runRequest(
                        $this->liselRequestCollection->get($requestType),
                        $liselRequest
                    );
                } catch (Exception $e) {
                    $liselRequest->setResponse($e->getMessage());
                    $liselRequest->setProcessed(date(static::PROCCESSED_DATE_FORMAT));
                    $liselRequest->save();
                }
            } else {
                throw new UnknownLiselRequestTypeException($requestType);
            }
        }
    }

    /**
     * @param \Shared\Zed\LiselRequest\Plugin\LiselRequestInterface $liselRequest
     * @param \Orm\Zed\LiselRequest\Persistence\NxsLiselRequest $liselRequestDetails
     *
     * @return void
     */
    private function runRequest(LiselRequestInterface $liselRequest, NxsLiselRequest $liselRequestDetails): void
    {
        $requestData = $this->requestFacade->getData($liselRequestDetails->getMessage());
        $requestData = $this->dateFormatFacade->formatArray($requestData, $this->config->getAutoConvertFieldNames());
        $liselRequest
            ->triggerEvents($requestData)
            ->storeRequest($requestData);
        $liselRequestDetails->setProcessed(date(static::PROCCESSED_DATE_FORMAT));
        $liselRequestDetails->save();
    }
}
