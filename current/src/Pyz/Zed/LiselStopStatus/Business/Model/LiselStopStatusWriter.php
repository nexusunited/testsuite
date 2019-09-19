<?php

namespace Pyz\Zed\LiselStopStatus\Business\Model;

use Generated\Shared\Transfer\LiselStopStatusTransfer;
use Orm\Zed\LiselStopStatus\Persistence\NxsStopStatus;
use Orm\Zed\LiselStopStatus\Persistence\NxsStopStatusQuery;
use Pyz\Shared\LiselStopStatus\LiselStopStatusConstants;

class LiselStopStatusWriter
{
    /**
     * @var \Orm\Zed\LiselStopStatus\Persistence\NxsStopStatus
     */
    private $nxsStopStatus;

    /**
     * @var \Generated\Shared\Transfer\LiselStopStatusTransfer
     */
    private $liselStopStatusTransfer;

    /**
     * @param \Orm\Zed\LiselStopStatus\Persistence\NxsStopStatus $nxsStopStatus
     * @param \Generated\Shared\Transfer\LiselStopStatusTransfer $transfer
     */
    public function __construct(
        NxsStopStatus $nxsStopStatus,
        LiselStopStatusTransfer $transfer
    ) {
        $this->nxsStopStatus = $nxsStopStatus;
        $this->liselStopStatusTransfer = $transfer;
    }

    /**
     * @param string $stoppId
     * @param bool $isIdle
     *
     * @return bool
     */
    public function toggleIdle(string $stoppId, bool $isIdle): bool
    {
        $success = true;

        if ($isIdle) {
            $success = $this->setStatus($stoppId, LiselStopStatusConstants::STATUS['Idle']);
        } else {
            $this->removeStatus($stoppId, LiselStopStatusConstants::STATUS['Idle']);
        }
        return $success;
    }

    /**
     * @param string $stoppId $stoppId
     * @param int $status
     *
     * @return bool
     */
    private function removeStatus(string $stoppId, int $status)
    {
        $success = false;
        $nxsStopStatusEntry = NxsStopStatusQuery::create()->filterByStoppId($stoppId)->filterByStatus($status)->findOne();
        if ($nxsStopStatusEntry) {
            $nxsStopStatusEntry->delete();
            $success = true;
        }
        return $success;
    }

    /**
     * @param string $stoppId
     * @param int $status
     *
     * @return bool
     */
    private function setStatus(string $stoppId, int $status): bool
    {
        $transfer = new LiselStopStatusTransfer();
        $transfer->setStoppId($stoppId);
        $transfer->setStatus($status);
        $transfer->setAta(date('Y-m-d H:i:s'));
        $record = $this->mapTransferToNxsStopStatus($transfer->toArray());
        $this->deleteStopStatusIfExist();
        return $record->save() > 0;
    }

    /**
     * @param array $requestDetails
     *
     * @return bool
     */
    public function storeRequest(array $requestDetails): bool
    {
        $record = $this->mapTransferToNxsStopStatus($requestDetails);
        $this->deleteStopStatusIfExist();
        return $record->save() > 0;
    }

    /**
     * @return void
     */
    private function deleteStopStatusIfExist(): void
    {
        $nxsStopStatus = NxsStopStatusQuery::create()
            ->findByStoppId($this->liselStopStatusTransfer->getStoppId());
        if ($nxsStopStatus->count() > 0) {
            $nxsStopStatus->delete();
        }
    }

    /**
     * @param array $requestDetails
     *
     * @return \Orm\Zed\LiselStopStatus\Persistence\NxsStopStatus
     */
    private function mapTransferToNxsStopStatus(array $requestDetails): NxsStopStatus
    {
        $this->liselStopStatusTransfer->fromArray($requestDetails);
        $this->nxsStopStatus->fromArray($this->liselStopStatusTransfer->toArray());
        return $this->nxsStopStatus;
    }
}
