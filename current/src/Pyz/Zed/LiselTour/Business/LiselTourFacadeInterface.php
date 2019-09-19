<?php

namespace Pyz\Zed\LiselTour\Business;

use Generated\Shared\Transfer\NxsTourEntityTransfer;
use Generated\Shared\Transfer\StoppListeTransfer;

interface LiselTourFacadeInterface
{
    /**
     * @param array $request $request
     *
     * @return bool
     */
    public function storeRequest(array $request): bool;

    /**
     * @param string $stopId $stopId
     *
     * @return \Generated\Shared\Transfer\StoppListeTransfer
     */
    public function getStopById(string $stopId): StoppListeTransfer;

    /**
     * @param string $stopId $stopId
     *
     * @return string
     */
    public function getTourNumber(string $stopId): string;

    /**
     * @param string $tourNumber
     *
     * @return \Generated\Shared\Transfer\NxsTourEntityTransfer
     */
    public function getTour(string $tourNumber): NxsTourEntityTransfer;
}
