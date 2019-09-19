<?php

namespace Pyz\Zed\LiselTour\Dependency\Plugin\Events;

use Orm\Zed\LiselTour\Persistence\NxsStopQuery;
use Pyz\Shared\LiselEvent\LiselEventConstants;

/**
 * @method \Shared\Zed\LiselTour\Business\LiselTourBusinessFactory getFactory()
 * @method \Shared\Zed\LiselTour\Business\LiselTourFacade getFacade()
 * @method \Shared\Zed\LiselTour\LiselTourConfig getConfig()
 */
class LiselFirstPtaEvent extends AbstractLiselTourEvent
{
    /**
     * @return string
     */
    public function getType(): string
    {
        return LiselEventConstants::FIRST_PTA;
    }

    /**
     * @return bool
     */
    public function isResponsible(): bool
    {
        $foundNxsStop = NxsStopQuery::create()->findOneByStoppId($this->getTransferData()->getStoppId());
        return $foundNxsStop === null &&
            ($this->getTransferData()->getPta() !== '' && $this->getTransferData()->getPta() !== null);
    }
}
