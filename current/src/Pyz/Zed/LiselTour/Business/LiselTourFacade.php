<?php

namespace Pyz\Zed\LiselTour\Business;

use Generated\Shared\Transfer\NxsTourEntityTransfer;
use Generated\Shared\Transfer\StoppListeTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Shared\Zed\LiselTour\Business\LiselTourBusinessFactory getFactory()
 */
class LiselTourFacade extends AbstractFacade implements LiselTourFacadeInterface
{
    /**
     * @param array $request $request
     *
     * @return bool
     */
    public function storeRequest(array $request): bool
    {
        return $this->getFactory()->createLiselTourWriter()->storeRequest($request);
    }

    /**
     * @param string $stopId $stopId
     *
     * @return \Generated\Shared\Transfer\StoppListeTransfer
     */
    public function getStopById(string $stopId): StoppListeTransfer
    {
        return $this->getFactory()->createLiselStopReader()->getStopToStopId($stopId);
    }

    /**
     * @param string $stopId $stopId
     *
     * @return string
     */
    public function getTourNumber(string $stopId): string
    {
        return $this->getFactory()->createLiselStopReader()->getTourNumber($stopId);
    }

    /**
     * @param string $tourNumber
     *
     * @return \Generated\Shared\Transfer\NxsTourEntityTransfer
     */
    public function getTour(string $tourNumber): NxsTourEntityTransfer
    {
        return $this->getFactory()->createLiselStopReader()->getTour($tourNumber);
    }
}
