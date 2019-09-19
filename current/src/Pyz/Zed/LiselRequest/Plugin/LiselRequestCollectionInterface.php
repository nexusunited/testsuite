<?php

namespace Pyz\Zed\LiselRequest\Plugin;

interface LiselRequestCollectionInterface
{
    /**
     * @param \Shared\Zed\LiselRequest\Plugin\LiselRequestInterface $plugin
     *
     * @return $this
     */
    public function add(LiselRequestInterface $plugin): self;

    /**
     * @param string $pluginType
     *
     * @return \Shared\Zed\LiselRequest\Plugin\LiselRequestInterface
     */
    public function get(string $pluginType): LiselRequestInterface;

    /**
     * @param string $pluginType
     *
     * @return bool
     */
    public function has(string $pluginType): bool;
}
