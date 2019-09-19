<?php

namespace Pyz\Zed\LiselRequest\Business\Model;

use Pyz\Zed\LiselRequest\Persistence\LiselRequestQueryContainer;
use function json_encode;

class LiselRequestWriter
{
    /**
     * @var \Shared\Zed\LiselRequest\Persistence\LiselRequestQueryContainer
     */
    private $queryContainer;

    /**
     * @param \Shared\Zed\LiselRequest\Persistence\LiselRequestQueryContainer $queryContainer
     */
    public function __construct(LiselRequestQueryContainer $queryContainer)
    {
        $this->queryContainer = $queryContainer;
    }

    /**
     * @param string $requestType $requestType
     * @param array $requestDetails $requestDetails
     *
     * @return void
     */
    public function storePlainRequest(string $requestType, array $requestDetails): void
    {
        $nxsLiselRequest = $this->queryContainer->createNxsLiselRequest();
        $nxsLiselRequest->setProcessed(false);
        $nxsLiselRequest->setRequestIdent($requestType);
        $nxsLiselRequest->setMessage(json_encode($requestDetails));
        $nxsLiselRequest->save();
    }
}
