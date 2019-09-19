<?php

namespace Pyz\Zed\LiselTime\Dependency\Plugin\Events;

use DateTime;
use Orm\Zed\LiselTime\Persistence\NxsTimeQuery;
use Orm\Zed\LiselTour\Persistence\NxsStopQuery;
use Pyz\Shared\LiselEvent\LiselEventConstants;
use Pyz\Zed\LiselStopStatus\Business\LiselStopStatusFacadeInterface;
use Pyz\Zed\MyDeliveries\Business\MyDeliveriesFacadeInterface;

/**
 * @method \Shared\Zed\LiselTime\Business\LiselTimeBusinessFactory getFactory()
 * @method \Shared\Zed\LiselTime\Business\LiselTimeFacade getFacade()
 * @method \Shared\Zed\LiselTime\LiselTimeConfig getConfig()
 */
class LiselUpdateEtaEvent extends AbstractLiselTimeEvent
{
    /**
     * @var \Shared\Zed\MyDeliveries\Business\MyDeliveriesFacadeInterface
     */
    private $deliveriesFacade;

    /**
     * @var \Shared\Zed\LiselStopStatus\Business\LiselStopStatusFacadeInterface
     */
    private $stopStatusFacade;

    /**
     * @param \Shared\Zed\MyDeliveries\Business\MyDeliveriesFacadeInterface $deliveriesFacade
     * @param \Shared\Zed\LiselStopStatus\Business\LiselStopStatusFacadeInterface $statusFacade
     */
    public function __construct(
        MyDeliveriesFacadeInterface $deliveriesFacade,
        LiselStopStatusFacadeInterface $statusFacade
    ) {
        $this->deliveriesFacade = $deliveriesFacade;
        $this->stopStatusFacade = $statusFacade;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return LiselEventConstants::ALWAYS_UPDATE_ETA_EVENT;
    }

    /**
     * @return bool
     */
    public function isResponsible(): bool
    {
        $isResponsible = false;
        $foundNxsStop = NxsStopQuery::create()->findOneByStoppId($this->getTransferData()->getStoppId());

        $nxsTimeItem = NxsTimeQuery::create()
            ->filterByStoppId($this->getTransferData()->getStoppId())
            ->findOne();

        if ($nxsTimeItem !== null && $foundNxsStop !== null && $this->getTransferData()->getEta() !== '') {
            $nxsStopStatus = $this->stopStatusFacade->getStopStatusByStoppId($this->getTransferData()->getStoppId());
            if ($nxsStopStatus === null) {
                $newTimeRange = $this->deliveriesFacade->getTimeSpan(
                    new DateTime($this->getTransferData()->getEta()),
                    $this->getConfig()->getEtaMinus(),
                    $this->getConfig()->getEtaPlus()
                );

                $existingTimeRange = $this->deliveriesFacade->getTimeSpan(
                    $nxsTimeItem->getEta(),
                    $this->getConfig()->getEtaMinus(),
                    $this->getConfig()->getEtaPlus()
                );

                if ($newTimeRange['start']->getTimeStamp() !== $existingTimeRange['start']->getTimeStamp()) {
                    $isResponsible = true;
                }
            }
        }

        return $isResponsible;
    }
}
