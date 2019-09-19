<?php

namespace Pyz\Zed\LiselRequest\Business;

interface LiselRequestFacadeInterface
{
    /**
     * @param string $requestType $requestType
     * @param array $requestDetails
     *
     * @return void
     */
    public function storePlainRequest(string $requestType, array $requestDetails): void;

    /**
     * @return int|null
     */
    public function getLastLiselRequestTimeStamp(): ?int;

    /**
     * @return void
     */
    public function setLastLiselRequestTimeStamp(): void;

    /**
     * @return void
     */
    public function startWorker(): void;
}
