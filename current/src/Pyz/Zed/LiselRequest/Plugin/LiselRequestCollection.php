<?php

namespace Pyz\Zed\LiselRequest\Plugin;

use Pyz\Zed\LiselRequest\Business\Exception\UnknownLiselRequestTypeException;
use function sprintf;

class LiselRequestCollection implements LiselRequestCollectionInterface
{
    /**
     * @var array
     */
    protected $plugins = [];

    /**
     * @param \Shared\Zed\LiselRequest\Plugin\LiselRequestInterface $plugin
     *
     * @return $this
     */
    public function add(LiselRequestInterface $plugin): LiselRequestCollectionInterface
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
     * @param string $pluginType
     *
     * @throws \Shared\Zed\LiselRequest\Business\Exception\UnknownLiselRequestTypeException
     *
     * @return \Shared\Zed\LiselRequest\Plugin\LiselRequestInterface
     */
    public function get(string $pluginType): LiselRequestInterface
    {
        if (empty($this->plugins[$pluginType])) {
            throw new UnknownLiselRequestTypeException(
                sprintf(
                    'Could not find "%s" plugin type. You need to add the needed plugins within your DependencyInjector.',
                    $pluginType
                )
            );
        }

        if (empty($this->plugins[$pluginType])) {
            throw new UnknownLiselRequestTypeException(
                sprintf(
                    'Could not find any plugin for "%s" provider. You need to add the needed plugins within your DependencyInjector.',
                    $pluginType
                )
            );
        }

        return $this->plugins[$pluginType];
    }
}
