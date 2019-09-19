<?php

namespace Pyz\Zed\LiselTour\Dependency\Plugin;

use Generated\Shared\Transfer\LiselTourTransfer;
use Pyz\Shared\LiselRequest\LiselRequestConstants;
use Pyz\Zed\LiselRequest\Plugin\AbstractLiselRequest;
use Pyz\Zed\LiselRequest\Plugin\LiselRequestInterface;

/**
 * @method \Shared\Zed\LiselTour\Dependency\LiselTourDependencyFactory getFactory()
 * @method \Shared\Zed\LiselTour\Business\LiselTourFacade getFacade()
 */
class LiselTourRequest extends AbstractLiselRequest implements LiselRequestInterface
{
    /**
     * @param array $requestDetails
     *
     * @return bool
     */
    public function storeRequest(array $requestDetails): bool
    {
        return $this->getFacade()->storeRequest($requestDetails);
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return LiselRequestConstants::LISEL_TOUR_TYPE;
    }

    /**
     * @param array $data
     *
     * @return \Shared\Zed\LiselRequest\Plugin\LiselRequestInterface
     */
    public function triggerEvents(array $data): LiselRequestInterface
    {
        $tourTransfer = $this->createTourTransfer($data);
        foreach ($tourTransfer->getStoppListe() as $stoppListeTransfer) {
            $this->getFactory()
                ->getLiselEventFacade()
                ->trigger($this->getFactory()->createEventPlugins(), $stoppListeTransfer);
        }
        return $this;
    }

    /**
     * @param array $data
     *
     * @return \Generated\Shared\Transfer\LiselTourTransfer
     */
    private function createTourTransfer(array $data): LiselTourTransfer
    {
        if (!empty($data['StoppListe'])) {
            foreach ($data['StoppListe'] as $index => $stop) {
                $data['StoppListe'][$index]['TourNummerTemp'] = $data['TourNummer'];
            }
        }

        $tourTransfer = new LiselTourTransfer();
        $tourTransfer->fromArray($data);

        return $tourTransfer;
    }
}
