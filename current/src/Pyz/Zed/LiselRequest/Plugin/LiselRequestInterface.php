<?php

namespace Pyz\Zed\LiselRequest\Plugin;

interface LiselRequestInterface
{
    /**
     * @param array $data
     *
     * @return bool
     */
    public function storeRequest(array $data): bool;

    /**
     * @return string
     */
    public function getType(): string;

    /**
     * @param array $data
     *
     * @return \Shared\Zed\LiselRequest\Plugin\LiselRequestInterface
     */
    public function triggerEvents(array $data): self;
}
