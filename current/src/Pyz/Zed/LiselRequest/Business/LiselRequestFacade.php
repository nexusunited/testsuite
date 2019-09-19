<?php

namespace Pyz\Zed\LiselRequest\Business;

use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Shared\Zed\LiselRequest\Business\LiselRequestBusinessFactory getFactory()
 */
class LiselRequestFacade extends AbstractFacade implements LiselRequestFacadeInterface
{
    /**
     * @param string $requestType $requestType
     * @param array $request $request
     *
     * @return void
     */
    public function storePlainRequest(string $requestType, array $request): void
    {
        $this->getFactory()->createLiselRequestWriter()->storePlainRequest($requestType, $request);
    }

    /**
     * @return int|null
     */
    public function getLastLiselRequestTimeStamp(): ?int
    {
        return $this->getFactory()->createLiselLastRequestTimestamp()->getTimestamp();
    }

    /**
     * @return void
     */
    public function setLastLiselRequestTimeStamp(): void
    {
        $this->getFactory()->createLiselLastRequestTimestamp()->setTimestamp();
    }

    /**
     * @return void
     */
    public function startWorker(): void
    {
        $this->getFactory()->createLiselRequestWorker()->startWorker();
    }
}
