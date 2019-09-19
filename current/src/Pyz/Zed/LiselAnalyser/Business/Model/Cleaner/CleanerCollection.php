<?php

namespace Pyz\Zed\LiselAnalyser\Business\Model\Cleaner;

use Pyz\Zed\LiselRequest\Business\Exception\UnknownLiselRequestTypeException;
use function sprintf;

class CleanerCollection implements CleanerCollectionInterface
{
    /**
     * @var array
     */
    protected $plugins = [];

    /**
     * @param \Shared\Zed\LiselAnalyser\Business\Model\Cleaner\CleanerInterface $plugin
     *
     * @return $this
     */
    public function add(CleanerInterface $plugin): CleanerCollectionInterface
    {
        $this->plugins[$plugin->getType()] = $plugin;

        return $this;
    }

    /**
     * @param string $pluginType
     *
     * @return bool
     */
    public function has(string $pluginType): bool
    {
        return isset($this->plugins[$pluginType]);
    }

    /**
     * @return \Shared\Zed\LiselAnalyser\Business\Model\Cleaner\CleanerInterface[]
     */
    public function getPlugins(): array
    {
        return $this->plugins;
    }

    /**
     * @param string $pluginType
     *
     * @throws \Shared\Zed\LiselRequest\Business\Exception\UnknownLiselRequestTypeException
     *
     * @return \Shared\Zed\LiselAnalyser\Business\Model\Cleaner\CleanerInterface
     */
    public function get(string $pluginType): CleanerInterface
    {
        if (empty($this->plugins[$pluginType])) {
            throw new UnknownLiselRequestTypeException(
                sprintf('Could not find "%s" plugin type. You need to add the needed plugins within your DependencyInjector.', $pluginType)
            );
        }

        if (empty($this->plugins[$pluginType])) {
            throw new UnknownLiselRequestTypeException(
                sprintf('Could not find any plugin for "%s" provider. You need to add the needed plugins within your DependencyInjector.', $pluginType)
            );
        }

        return $this->plugins[$pluginType];
    }
}
