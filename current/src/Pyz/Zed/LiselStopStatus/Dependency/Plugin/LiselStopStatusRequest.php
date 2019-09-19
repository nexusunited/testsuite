<?php

namespace Pyz\Zed\LiselStopStatus\Dependency\Plugin;

use Generated\Shared\Transfer\LiselStopStatusTransfer;
use Pyz\Shared\LiselRequest\LiselRequestConstants;
use Pyz\Zed\LiselRequest\Plugin\AbstractLiselRequest;
use Pyz\Zed\LiselRequest\Plugin\LiselRequestInterface;

/**
 * @method \Shared\Zed\LiselStopStatus\Dependency\LiselStopStatusDependencyFactory getFactory()
 * @method \Shared\Zed\LiselStopStatus\Business\LiselStopStatusFacade getFacade()
 */
class LiselStopStatusRequest extends AbstractLiselRequest implements LiselRequestInterface
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
        return LiselRequestConstants::LISEL_STATUS_TYPE;
    }

    /**
     * @param array $data
     *
     * @return $this
     */
    public function triggerEvents(array $data): LiselRequestInterface
    {
        $stopStatusTransfer = $this->createStopStatusTransfer($data);
        $this->getFactory()
            ->getLiselEventFacade()
            ->trigger($this->getFactory()->createEventPlugins(), $stopStatusTransfer);
        return $this;
    }

    /**
     * @param array $data
     *
     * @return \Generated\Shared\Transfer\LiselStopStatusTransfer
     */
    private function createStopStatusTransfer(array $data): LiselStopStatusTransfer
    {
        $tourTransfer = new LiselStopStatusTransfer();
        $tourTransfer->fromArray($data);
        return $tourTransfer;
    }
}
