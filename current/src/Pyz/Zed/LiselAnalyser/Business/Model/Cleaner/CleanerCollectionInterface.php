<?php

namespace Pyz\Zed\LiselAnalyser\Business\Model\Cleaner;

interface CleanerCollectionInterface
{
    /**
     * @param \Shared\Zed\LiselAnalyser\Business\Model\Cleaner\CleanerInterface $plugin
     *
     * @return $this
     */
    public function add(CleanerInterface $plugin): self;

    /**
     * @param string $pluginType
     *
     * @return bool
     */
    public function has(string $pluginType): bool;

    /**
     * @param string $pluginType
     *
     * @return \Shared\Zed\LiselAnalyser\Business\Model\Cleaner\CleanerInterface
     */
    public function get(string $pluginType): CleanerInterface;

    /**
     * @return \Shared\Zed\LiselAnalyser\Business\Model\Cleaner\CleanerInterface[]
     */
    public function getPlugins(): array;
}
