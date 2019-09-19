<?php

namespace Pyz\Zed\LiselTime\Dependency\Plugin;

use Generated\Shared\Transfer\LiselTimeMessagesTransfer;
use Generated\Shared\Transfer\LiselTimeTransfer;
use Pyz\Shared\LiselRequest\LiselRequestConstants;
use Pyz\Zed\LiselRequest\Plugin\AbstractLiselRequest;
use Pyz\Zed\LiselRequest\Plugin\LiselRequestInterface;

/**
 * @method \Shared\Zed\LiselTime\Dependency\LiselTimeDependencyFactory getFactory()
 * @method \Shared\Zed\LiselTime\Business\LiselTimeFacade getFacade()
 */
class LiselTimeRequest extends AbstractLiselRequest implements LiselRequestInterface
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
        return LiselRequestConstants::LISEL_TIME_TYPE;
    }

    /**
     * @param array $data
     *
     * @return \Shared\Zed\LiselRequest\Plugin\LiselRequestInterface
     */
    public function triggerEvents(array $data): LiselRequestInterface
    {
        if (!empty($data)) {
            $timeTransfer = new LiselTimeMessagesTransfer();
            $timeTransfer->fromArray($data);

            foreach ($timeTransfer->getSingleTimeMessages() as $timeTransfer) {
                $this->getFactory()
                    ->getLiselEventFacade()
                    ->trigger($this->getFactory()->createEventPlugins(), $timeTransfer);
            }
        }
        return $this;
    }

    /**
     * @param array $data
     *
     * @return \Generated\Shared\Transfer\LiselTimeTransfer
     */
    private function createTimeTransfer(array $data): LiselTimeTransfer
    {
        $tourTransfer = new LiselTimeTransfer();
        $tourTransfer->fromArray($data);
        return $tourTransfer;
    }
}
