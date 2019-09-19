<?php

namespace Pyz\Zed\LiselTour\Dependency\Plugin\Events;

use DateTime;
use Orm\Zed\LiselTour\Persistence\NxsStopQuery;
use Pyz\Shared\LiselEvent\LiselEventConstants;
use Pyz\Zed\MyDeliveries\Business\MyDeliveriesFacadeInterface;

/**
 * @method \Shared\Zed\LiselTour\Business\LiselTourBusinessFactory getFactory()
 * @method \Shared\Zed\LiselTour\Business\LiselTourFacade getFacade()
 * @method \Shared\Zed\LiselTour\LiselTourConfig getConfig()
 */
class LiselUpdatePtaEvent extends AbstractLiselTourEvent
{
    /**
     * @var \Shared\Zed\MyDeliveries\Business\MyDeliveriesFacadeInterface
     */
    private $deliveriesFacade;

    /**
     * @param \Shared\Zed\MyDeliveries\Business\MyDeliveriesFacadeInterface $deliveriesFacade
     */
    public function __construct(MyDeliveriesFacadeInterface $deliveriesFacade)
    {
        $this->deliveriesFacade = $deliveriesFacade;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return LiselEventConstants::UPDATE_PTA;
    }

    /**
     * @return bool
     */
    public function isResponsible(): bool
    {
        $isResponsible = false;

        $foundNxsStop = NxsStopQuery::create()->findOneByStoppId($this->getTransferData()->getStoppId());

        if ($foundNxsStop !== null && $this->getTransferData()->getPta() !== '') {
            $newStopRange = $this->deliveriesFacade->getTimeSpan(
                new DateTime($this->getTransferData()->getPta()),
                $this->getConfig()->getPtaMinus(),
                $this->getConfig()->getPtaPlus()
            );

            $existingStopRange = $this->deliveriesFacade->getTimeSpan(
                $foundNxsStop->getPta(),
                $this->getConfig()->getPtaMinus(),
                $this->getConfig()->getPtaPlus()
            );

            if ($newStopRange['start']->getTimeStamp() !== $existingStopRange['start']->getTimeStamp()) {
                $isResponsible = true;
            }
        }

        return $isResponsible;
    }
}
