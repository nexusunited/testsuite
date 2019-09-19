<?php

namespace Pyz\Zed\LiselTime\Business\Model;

use Exception;
use Generated\Shared\Transfer\LiselTimeMessagesTransfer;
use Generated\Shared\Transfer\LiselTimeTransfer;
use Pyz\Shared\Log\LoggerTrait;
use Pyz\Zed\LiselStopStatus\Business\LiselStopStatusFacadeInterface;
use Pyz\Zed\LiselTime\Business\Exception\TimeMessageNotSavedException;
use Pyz\Zed\LiselTime\Persistence\LiselTimeQueryContainer;

class LiselTimeWriter
{
    use LoggerTrait;

    /**
     * @var \Shared\Zed\LiselTime\Persistence\LiselTimeQueryContainer
     */
    private $queryContainer;

    /**
     * @var \Shared\Zed\LiselStopStatus\Business\LiselStopStatusFacadeInterface
     */
    private $stopStatusFacade;

    /**
     * @param \Shared\Zed\LiselTime\Persistence\LiselTimeQueryContainer $queryContainer
     * @param \Shared\Zed\LiselStopStatus\Business\LiselStopStatusFacadeInterface $stopStatusFacade
     */
    public function __construct(
        LiselTimeQueryContainer $queryContainer,
        LiselStopStatusFacadeInterface $stopStatusFacade
    ) {
        $this->queryContainer = $queryContainer;
        $this->stopStatusFacade = $stopStatusFacade;
    }

    /**
     * @param array $tourDetails
     *
     * @throws \Exception
     *
     * @return bool
     */
    public function storeRequest(array $tourDetails): bool
    {
        try {
            $timeTransfer = $this->createTimeMessagesTransfer($tourDetails);
            $this->addSaveNxsTimeMessages($timeTransfer);
        } catch (Exception $e) {
            $this->logError(__CLASS__ . ' ' . __METHOD__, ['Exception Details' => $e->getMessage()]);
            throw $e;
        }
        return true;
    }

    /**
     * @param \Generated\Shared\Transfer\LiselTimeMessagesTransfer $timeTransfer
     *
     * @return void
     */
    private function addSaveNxsTimeMessages(LiselTimeMessagesTransfer $timeTransfer): void
    {
        if (count($timeTransfer->getSingleTimeMessages()) > 0) {
            $this->loopTimeMessages($timeTransfer);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\LiselTimeTransfer $timeMessage
     *
     * @return bool
     */
    private function saveTimeMessage(LiselTimeTransfer $timeMessage): bool
    {
        $timeMessage->setAta('');
        $nxsTimeQuery = $this->queryContainer->createNxsTimeQuery()->findOneByStoppId($timeMessage->getStoppId());
        if ($nxsTimeQuery !== null) {
            $nxsTimeQuery->delete();
        }
        $nxsCreateTimeQuery = $this->queryContainer->queryNxsTime();
        $nxsCreateTimeQuery->fromArray($timeMessage->toArray());
        return $nxsCreateTimeQuery->save() > 0;
    }

    /**
     * @param \Generated\Shared\Transfer\LiselTimeMessagesTransfer $singleTimeMessages
     *
     * @throws \Shared\Zed\LiselTime\Business\Exception\TimeMessageNotSavedException
     *
     * @return void
     */
    private function loopTimeMessages(LiselTimeMessagesTransfer $singleTimeMessages): void
    {
        foreach ($singleTimeMessages->getSingleTimeMessages() as $timeMessage) {
            if (!$this->saveTimeMessage($timeMessage)) {
                throw new TimeMessageNotSavedException($timeMessage->getMessageId());
            }
            $this->stopStatusFacade->toggleIdle($timeMessage->getStoppId(), false);
        }
    }

    /**
     * @param array $tourDetails
     *
     * @return \Generated\Shared\Transfer\LiselTimeMessagesTransfer
     */
    private function createTimeMessagesTransfer(array $tourDetails): LiselTimeMessagesTransfer
    {
        $timeTransfer = new LiselTimeMessagesTransfer();
        $timeTransfer->fromArray($tourDetails);
        return $timeTransfer;
    }
}
