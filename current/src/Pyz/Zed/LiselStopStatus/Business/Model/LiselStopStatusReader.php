<?php

namespace Pyz\Zed\LiselStopStatus\Business\Model;

use Generated\Shared\Transfer\NxsStopStatusEntityTransfer;
use Orm\Zed\LiselStopStatus\Persistence\NxsStopStatus;
use Propel\Runtime\ActiveQuery\Criteria;
use Pyz\Shared\LiselStopStatus\LiselStopStatusConstants;
use Pyz\Zed\LiselStopStatus\Persistence\LiselStopStatusQueryContainer;

class LiselStopStatusReader
{
    /**
     * @var \Shared\Zed\LiselStopStatus\Persistence\LiselStopStatusQueryContainer
     */
    private $queryContainer;

    /**
     * @param \Shared\Zed\LiselStopStatus\Persistence\LiselStopStatusQueryContainer $queryContainer
     */
    public function __construct(LiselStopStatusQueryContainer $queryContainer)
    {
        $this->queryContainer = $queryContainer;
    }

    /**
     * @param string $stoppId
     *
     * @return \Generated\Shared\Transfer\NxsStopStatusEntityTransfer|null
     */
    public function getStopStatusByStoppId(string $stoppId): ?NxsStopStatusEntityTransfer
    {
        $entity = $this->queryContainer->queryNxsStopStatusQuery()
            ->filterByStatus(LiselStopStatusConstants::STATUS['ContinueETA'], Criteria::NOT_EQUAL)
            ->findOneByStoppId($stoppId);

        if (!$entity) {
            return null;
        }

        return $this->mapToTransfer($entity);
    }

    /**
     * @param \Orm\Zed\LiselStopStatus\Persistence\NxsStopStatus $status
     *
     * @return \Generated\Shared\Transfer\NxsStopStatusEntityTransfer
     */
    private function mapToTransfer(NxsStopStatus $status): NxsStopStatusEntityTransfer
    {
        return (new NxsStopStatusEntityTransfer())->fromArray($status->toArray(), true);
    }
}
