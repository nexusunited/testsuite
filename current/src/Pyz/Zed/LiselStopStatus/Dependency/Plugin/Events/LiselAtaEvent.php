<?php

namespace Pyz\Zed\LiselStopStatus\Dependency\Plugin\Events;

use Exception;
use Pyz\Shared\LiselEvent\LiselEventConstants;
use Pyz\Zed\LiselTour\Business\LiselTourFacadeInterface;

/**
 * @method \Shared\Zed\LiselStopStatus\Business\LiselStopStatusBusinessFactory getFactory()
 * @method \Shared\Zed\LiselStopStatus\Business\LiselStopStatusFacade getFacade()
 */
class LiselAtaEvent extends AbstractLiselStopStatusEvent
{
    /**
     * @var \Shared\Zed\LiselTour\Business\LiselTourFacadeInterface
     */
    private $liselTourFacade;

    /**
     * @param \Shared\Zed\LiselTour\Business\LiselTourFacadeInterface $liselTourFacade
     */
    public function __construct(LiselTourFacadeInterface $liselTourFacade)
    {
        $this->liselTourFacade = $liselTourFacade;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return LiselEventConstants::LISEL_ATA;
    }

    /**
     * @return bool
     */
    public function isResponsible(): bool
    {
        $isResponsible = false;
        try {
            $this->liselTourFacade->getStopById($this->getTransferData()->getStoppId());
            if ($this->hasAta() && $this->isDelivered()) {
                $isResponsible = true;
            }
        } catch (Exception $e) {
            $isResponsible = false;
        }
        return $isResponsible;
    }
}
