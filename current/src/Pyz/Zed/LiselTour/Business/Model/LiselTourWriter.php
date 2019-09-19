<?php

namespace Pyz\Zed\LiselTour\Business\Model;

use Exception;
use Generated\Shared\Transfer\AuftragListeTransfer;
use Generated\Shared\Transfer\LiselTourTransfer;
use Generated\Shared\Transfer\StoppListeTransfer;
use Orm\Zed\LiselTour\Persistence\NxsArticle;
use Orm\Zed\LiselTour\Persistence\NxsOrder;
use Orm\Zed\LiselTour\Persistence\NxsStop;
use Orm\Zed\LiselTour\Persistence\NxsStopQuery;
use Orm\Zed\LiselTour\Persistence\NxsTour;
use Orm\Zed\LiselTour\Persistence\NxsTourQuery;
use Pyz\Shared\Log\LoggerTrait;
use Pyz\Zed\LiselStopStatus\Business\LiselStopStatusFacadeInterface;
use Pyz\Zed\LiselTour\Persistence\LiselTourQueryContainerInterface;

class LiselTourWriter
{
    use LoggerTrait;

    /**
     * @var \Shared\Zed\LiselTour\Persistence\LiselTourQueryContainerInterface
     */
    private $queryContainer;

    /**
     * @var \Shared\Zed\LiselStopStatus\Business\LiselStopStatusFacadeInterface
     */
    private $stopStatusFacade;

    /**
     * @param \Shared\Zed\LiselTour\Persistence\LiselTourQueryContainerInterface $queryContainer
     * @param \Shared\Zed\LiselStopStatus\Business\LiselStopStatusFacadeInterface $stopStatusFacade
     */
    public function __construct(
        LiselTourQueryContainerInterface $queryContainer,
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
            $tourTransfer = (new LiselTourTransfer())->fromArray($tourDetails);

            $this->deleteTourIfExists($tourTransfer);
            $this->deleteStopsIfExist($tourTransfer);

            $success = $this->mapTransferToNxsTour($tourTransfer)->save() > 0;
            $this->removeIdleForStops($tourTransfer);
        } catch (Exception $e) {
            $this->logError(__CLASS__ . ' ' . __METHOD__, ['Exception Details' => $e->getMessage()]);
            throw $e;
        }
        return $success;
    }

    /**
     * @param \Generated\Shared\Transfer\LiselTourTransfer $tourTransfer
     *
     * @return \Orm\Zed\LiselTour\Persistence\NxsTour
     */
    private function mapTransferToNxsTour(LiselTourTransfer $tourTransfer): NxsTour
    {
        $nxsTour = $this->queryContainer->createNxsTour();
        $nxsTour->fromArray($tourTransfer->toArray());

        $this->addStopsToTour($nxsTour, $tourTransfer);

        return $nxsTour;
    }

    /**
     * @param \Generated\Shared\Transfer\AuftragListeTransfer $order $order
     * @param \Orm\Zed\LiselTour\Persistence\NxsOrder $nxsOrder $nxsOrder
     *
     * @return void
     */
    private function addArticleToOrder(AuftragListeTransfer $order, NxsOrder $nxsOrder): void
    {
        foreach ($order->getArtikelListe() as $article) {
            $nxsArticle = new NxsArticle();
            $nxsArticle->fromArray($article->toArray());
            $nxsOrder->addNxsArticle($nxsArticle);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\StoppListeTransfer $stop $stop
     * @param \Orm\Zed\LiselTour\Persistence\NxsStop $nxsStoppListe $nxsStoppListe
     *
     * @return void
     */
    private function addOrderToStopList(StoppListeTransfer $stop, NxsStop $nxsStoppListe): void
    {
        foreach ($stop->getAuftragListe() as $order) {
            $nxsOrder = new NxsOrder();
            $nxsOrder->fromArray($order->toArray());
            $nxsStoppListe->addNxsOrder($nxsOrder);

            $this->addArticleToOrder($order, $nxsOrder);
        }
    }

    /**
     * @param \Orm\Zed\LiselTour\Persistence\NxsTour $nxsTour
     * @param \Generated\Shared\Transfer\LiselTourTransfer $tourTransfer
     *
     * @return void
     */
    private function addStopsToTour(NxsTour $nxsTour, LiselTourTransfer $tourTransfer): void
    {
        foreach ($tourTransfer->getStoppListe() as $stop) {
            if ($stop->getInvoiceNumber() === null) {
                $stop->setInvoiceNumber('');
            }
            $nxsStoppListe = new NxsStop();
            $nxsStoppListe->fromArray($stop->toArray());
            $nxsTour->addNxsStop($nxsStoppListe);

            $this->addOrderToStopList($stop, $nxsStoppListe);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\LiselTourTransfer $tourTransfer
     *
     * @return void
     */
    private function deleteTourIfExists(LiselTourTransfer $tourTransfer): void
    {
        $tourActiveRecord = NxsTourQuery::create()
            ->findByTourNummer($tourTransfer->getTourNummer());
        if ($tourActiveRecord->count() > 0) {
            $tourActiveRecord->delete();
        }
    }

    /**
     * @param \Generated\Shared\Transfer\LiselTourTransfer $tourTransfer
     *
     * @return void
     */
    private function deleteStopsIfExist(LiselTourTransfer $tourTransfer): void
    {
        foreach ($tourTransfer->getStoppListe() as $stop) {
            $stopEntity = NxsStopQuery::create()
                ->findByStoppId($stop->getStoppId());

            if ($stopEntity->count() > 0) {
                $stopEntity->delete();
            }
        }
    }

    /**
     * @param \Generated\Shared\Transfer\LiselTourTransfer $tourTransfer
     *
     * @return void
     */
    private function removeIdleForStops(LiselTourTransfer $tourTransfer): void
    {
        foreach ($tourTransfer->getStoppListe() as $stopp) {
            $this->stopStatusFacade->toggleIdle($stopp->getStoppId(), false);
        }
    }
}
