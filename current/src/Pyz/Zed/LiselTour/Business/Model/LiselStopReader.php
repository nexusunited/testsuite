<?php

namespace Pyz\Zed\LiselTour\Business\Model;

use Generated\Shared\Transfer\NxsTourEntityTransfer;
use Generated\Shared\Transfer\StoppListeTransfer;
use Pyz\Zed\Lisel\Business\Exception\StopNotFoundException;
use Pyz\Zed\Lisel\Business\Exception\TourNotFoundException;
use Pyz\Zed\LiselTour\Persistence\LiselTourQueryContainerInterface;
use function count;

class LiselStopReader
{
    /**
     * @var \Shared\Zed\LiselTour\Persistence\LiselTourQueryContainerInterface
     */
    private $queryContainer;

    /**
     * @param \Portal\Zed\LiselTour\Persistence\LiselTourQueryContainerInterface $queryContainer
     */
    public function __construct(LiselTourQueryContainerInterface $queryContainer)
    {
        $this->queryContainer = $queryContainer;
    }

    /**
     * @param string $stopId $stopId
     *
     * @throws \Shared\Zed\Lisel\Business\Exception\StopNotFoundException
     *
     * @return \Generated\Shared\Transfer\StoppListeTransfer
     */
    public function getStopToStopId(string $stopId): StoppListeTransfer
    {
        $nxsStopQuery = $this->queryContainer->queryNxsStop()
            ->findOneByStoppId($stopId);
        $stopListeTransfer = new StoppListeTransfer();
        if ($nxsStopQuery === null) {
            throw new StopNotFoundException($stopId);
        }
        $stopListeTransfer->fromArray($nxsStopQuery->toArray(), true);
        return $stopListeTransfer;
    }

    /**
     * @param string $stopId
     *
     * @throws \Shared\Zed\Lisel\Business\Exception\StopNotFoundException
     *
     * @return string
     */
    public function getTourNumber(string $stopId): string
    {
        $nxsStopQuery = $this->queryContainer->queryNxsStop()->joinWithNxsTour()
            ->findOneByStoppId($stopId);

        if ($nxsStopQuery === null) {
            throw new StopNotFoundException($stopId);
        }

        return $nxsStopQuery->getNxsTour()->getTourNummer();
    }

    /**
     * @param string $tourNumber
     *
     * @throws \Shared\Zed\Lisel\Business\Exception\TourNotFoundException
     *
     * @return \Generated\Shared\Transfer\NxsTourEntityTransfer
     */
    public function getTour(string $tourNumber): NxsTourEntityTransfer
    {
        $nxsTours = $this->queryContainer->queryNxsTour()
            ->leftJoinWithNxsStop()
            ->findByTourNummer($tourNumber);

        if (count($nxsTours) === 0) {
            throw new TourNotFoundException($tourNumber);
        }

        return (new NxsTourEntityTransfer())->fromArray($nxsTours->toArray()[0]);
    }
}
